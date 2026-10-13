<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * The launch articles. Safe to run again: posts are matched by slug and never duplicated.
 */
class PostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('is_admin', true)->first();

        foreach ($this->posts() as $post) {
            Post::updateOrCreate(
                ['slug' => $post['slug']],
                [
                    ...$post,
                    'user_id' => $author?->id,
                    'status' => Post::STATUS_PUBLISHED,
                ],
            );
        }
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function posts(): array
    {
        return [
            [
                'slug' => 'revenue-cycle-step-by-step',
                'title' => 'The Revenue Cycle, Step by Step: Where Practices Lose Money',
                'category' => 'Revenue Cycle',
                'published_at' => '2026-09-22',
                'artwork' => 'analytics',
                'excerpt' => 'Follow a claim from the first phone call to the final payment and see where revenue most often slips away.',
                'body' => <<<'MD'
Every visit starts a chain of tasks that ends, ideally, with a payment. When one link is weak, revenue leaks. Here is the chain, and the weak points we see most often.

## 1. Scheduling and registration

Revenue protection starts with accurate patient details. A misspelled name or an old member ID can cause a rejection weeks later.

## 2. Eligibility and authorization

Checking coverage and authorization requirements before the visit prevents some of the most common and most avoidable denials.

## 3. Documentation and coding

Codes must reflect the documentation. Missing modifiers and vague diagnoses are frequent causes of underpayment.

## 4. Claim submission

Claims should be checked for errors and submitted promptly, well inside each payer's filing limit.

## 5. Payment posting

Every remittance should be reviewed, not just posted. Partial payments and denials need action, not a quiet write-off.

## 6. Follow-up and patient billing

Unpaid claims and patient balances need consistent, scheduled follow-up. Without it, money ages until it is no longer collectable.

## The takeaway

Most revenue problems are process problems. Look at each step, measure it and fix the weakest link first.
MD,
            ],
            [
                'slug' => 'common-claim-denials',
                'title' => 'Seven Common Claim Denials and How to Prevent Them',
                'category' => 'Denials',
                'published_at' => '2026-08-30',
                'artwork' => 'claim',
                'excerpt' => 'The denial reasons we see again and again, and the simple habits that prevent most of them.',
                'body' => <<<'MD'
Denials cost time to rework and some are never recovered. The good news is that most come from a short list of causes.

## The usual suspects

1. **Inactive coverage:** verify eligibility before every visit.
2. **Missing prior authorization:** check requirements when the appointment is booked.
3. **Patient data errors:** confirm names, dates of birth and member IDs at check-in.
4. **Missing or wrong modifiers:** review modifier use for procedures billed together.
5. **Medical necessity:** make sure the diagnosis supports the service.
6. **Duplicate claims:** check status before resubmitting.
7. **Timely filing:** know every payer's deadline and submit early.

## Make prevention a habit

Review your denials by reason each month. Pick the top three, find where each one starts and agree on one change to stop it. Repeat every month and your denial rate will steadily fall.
MD,
            ],
            [
                'slug' => 'in-house-vs-outsourced-billing',
                'title' => 'In-House or Outsourced Billing? A Practical Comparison',
                'category' => 'Practice Management',
                'published_at' => '2026-08-04',
                'artwork' => 'support',
                'excerpt' => 'Weighing the real costs and trade-offs of running billing yourself versus working with a billing partner.',
                'body' => <<<'MD'
There is no single right answer for every practice, but there are clear questions that point to the right choice for yours.

## The true cost of in-house billing

Salaries are only the start. Add benefits, training, software licences, clearinghouse fees, office space and the cost of covering holidays and turnover.

## What outsourcing changes

A billing partner brings a trained team, established processes and specialists for coding, AR and credentialing. You pay for results rather than headcount.

## Questions to ask yourself

- How long are claims waiting before they are submitted?
- How much of your accounts receivable is older than 90 days?
- What happens to billing when a key staff member leaves?
- Do you have time to keep up with coding and payer changes?

## A sensible approach

Start with a review of your current numbers. If the gaps are small, targeted help may be enough. If they are large, full revenue cycle support usually pays for itself.
MD,
            ],
            [
                'slug' => 'prior-authorizations-without-bottlenecks',
                'title' => 'Prior Authorizations Without the Bottleneck',
                'category' => 'Front Desk',
                'published_at' => '2026-07-14',
                'artwork' => 'calendar',
                'excerpt' => 'A simple workflow for getting authorizations approved on time without overwhelming your staff.',
                'body' => <<<'MD'
Prior authorization delays frustrate patients and providers alike. A clear workflow keeps requests moving.

## Start at scheduling

Ask at booking whether the service is likely to need authorization. The earlier a request starts, the less likely the appointment will need to move.

## Keep a payer reference

Maintain a simple list of services that each major payer requires authorization for. Update it whenever a payer changes its rules.

## Submit complete requests

Most delays come from missing clinical information. Attach the notes, test results and history that support medical necessity the first time.

## Track every request

Use one list with the patient, service, payer, date submitted and status. Follow up on anything pending for more than a few business days.

## Close the loop

Record the authorization number and approved dates where your billers will see them, so the claim matches the approval exactly.
MD,
            ],
            [
                'slug' => 'cost-of-slow-credentialing',
                'title' => 'What Slow Credentialing Really Costs Your Practice',
                'category' => 'Credentialing',
                'published_at' => '2026-06-18',
                'artwork' => 'credential',
                'excerpt' => 'Every week a provider cannot bill is revenue you will never get back. Here is how to keep enrollment on track.',
                'body' => <<<'MD'
When a new provider joins before their payer enrollments are complete, they either see fewer patients or see patients they cannot bill for. Both are expensive.

## Where delays come from

- Applications submitted with missing or outdated documents
- CAQH profiles that have not been attested recently
- Applications sent late because nobody owned the process
- Payers requesting extra information that goes unanswered

## How to stay ahead

- Start enrollment as soon as a start date is agreed
- Keep a ready-to-go document set for every provider
- Assign one person to own follow-up with each payer
- Track revalidation dates so existing enrollments never lapse

## The bottom line

Credentialing is mostly paperwork and persistence. Treat it like a project with deadlines and an owner, and it stops being a source of lost revenue.
MD,
            ],
            [
                'slug' => 'coding-accuracy-habits',
                'title' => 'Coding Accuracy: Small Habits That Protect Your Revenue',
                'category' => 'Coding',
                'published_at' => '2026-05-27',
                'artwork' => 'security',
                'excerpt' => 'Practical documentation and coding habits that reduce denials and keep audits uneventful.',
                'body' => <<<'MD'
Accurate coding protects you twice: it gets you paid correctly today and keeps you safe if your claims are ever audited.

## Document for the next reader

Write notes that would make sense to someone who was not in the room. Specific diagnoses, clear plans and signed, dated entries make coding straightforward.

## Be specific with diagnoses

Unspecified codes are sometimes appropriate, but they often invite questions. When the documentation supports more detail, code to the highest specificity.

## Know your modifiers

Learn the modifiers your specialty uses most and when they apply. Misused modifiers are a leading cause of bundling denials.

## Keep up with changes

Code sets update every year. Make time to review the changes that affect your specialty before they take effect.

## Audit yourself

Review a small sample of claims every quarter. Finding a problem yourself is far better than having a payer find it for you.
MD,
            ],
        ];
    }
}
