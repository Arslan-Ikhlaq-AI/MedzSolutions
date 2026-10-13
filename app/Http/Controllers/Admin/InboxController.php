<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateInboxItemRequest;
use App\Support\AdminInbox;
use App\Support\VisitorJourney;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

/**
 * Lists, shows, updates and deletes one kind of website request. Subclasses say which kind
 * and which fields to display.
 */
abstract class InboxController extends Controller
{
    public function __construct(protected AdminInbox $inbox) {}

    /**
     * Key of the section in {@see AdminInbox::sections()}.
     */
    abstract protected function key(): string;

    /**
     * Table columns after the first one, as label => closure that returns the cell text.
     *
     * @return array<string, Closure(Model): string>
     */
    abstract protected function columns(): array;

    /**
     * Fields shown on the detail page. "kind" is one of text, email, phone, link, long or tags.
     *
     * @return array<int, array{label: string, value: mixed, kind?: string}>
     */
    abstract protected function details(Model $record): array;

    /**
     * Database columns the search box looks in.
     *
     * @return array<int, string>
     */
    abstract protected function searchColumns(): array;

    /**
     * @return Builder<Model>
     */
    protected function query(Request $request): Builder
    {
        return $this->section()['model']::query();
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    protected function tabs(): array
    {
        return [];
    }

    public function index(Request $request): View
    {
        $section = $this->section();
        $search = $request->string('q')->trim()->value();
        $status = RequestStatus::tryFrom($request->string('status')->value());
        $onlyUnread = $request->query('filter') === 'unread';

        $records = $this->query($request)
            ->when($status, fn (Builder $query) => $query->where('status', $status->value))
            ->when($onlyUnread, fn (Builder $query) => $query->unread())
            ->when($search !== '', function (Builder $query) use ($search): void {
                $term = '%'.addcslashes($search, '%_\\').'%';

                $query->where(function (Builder $query) use ($term): void {
                    foreach ($this->searchColumns() as $column) {
                        $query->orWhere($column, 'like', $term);
                    }
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.inbox.index', [
            'section' => $section,
            'sectionKey' => $this->key(),
            'records' => $records,
            'rows' => $records->getCollection()->map(fn (Model $record): array => [
                'id' => $record->getKey(),
                'unread' => $record->isUnread(),
                'title' => ($section['headline'])($record),
                'subtitle' => ($section['summary'])($record),
                'cells' => collect($this->columns())->map(fn (Closure $cell): string => $cell($record))->values()->all(),
                'status' => $record->status,
                'received' => $record->created_at,
            ]),
            'columnLabels' => array_keys($this->columns()),
            'tabs' => $this->tabs(),
            'filters' => ['q' => $search, 'status' => $status?->value, 'filter' => $onlyUnread ? 'unread' : null, 'type' => $request->query('type')],
            'statusOptions' => RequestStatus::options(),
        ]);
    }

    public function show(string $id, VisitorJourney $journeys): View
    {
        $record = $this->find($id);
        $record->markAsRead();

        return view('admin.inbox.show', [
            'section' => $this->section(),
            'sectionKey' => $this->key(),
            'record' => $record,
            'title' => ($this->section()['headline'])($record),
            'details' => $this->details($record),
            'statusOptions' => RequestStatus::options(),
            'extraActions' => $this->extraActions($record),
            'journey' => $journeys->before($record->visitor_id, $record->created_at),
        ]);
    }

    public function update(UpdateInboxItemRequest $request, string $id): RedirectResponse
    {
        $this->find($id)->forceFill($request->validated())->save();

        return back()->with('status', 'Changes saved.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $record = $this->find($id);
        $this->beforeDelete($record);
        $record->delete();

        return redirect()
            ->route($this->section()['route'].'.index')
            ->with('status', $this->section()['singular'].' deleted.');
    }

    /**
     * Extra buttons for the detail page, as label => URL.
     *
     * @return array<string, string>
     */
    protected function extraActions(Model $record): array
    {
        return [];
    }

    protected function beforeDelete(Model $record): void {}

    /**
     * @return array{model: class-string<Model>, title: string, singular: string, icon: string, route: string, headline: Closure(Model): string, summary: Closure(Model): string}
     */
    protected function section(): array
    {
        return $this->inbox->section($this->key());
    }

    protected function find(string $id): Model
    {
        return $this->section()['model']::findOrFail($id);
    }

    /**
     * Joins the non-empty values with a separator, for compact table cells.
     *
     * @param  array<int, mixed>  $parts
     */
    protected function joined(array $parts, string $separator = ' · '): string
    {
        return implode($separator, array_filter(Arr::flatten($parts), fn ($part) => filled($part))) ?: '—';
    }
}
