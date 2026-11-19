#!/usr/bin/env bash
#
# Local: build frontend assets, commit everything and push to GitHub.
#
# Usage:
#   ./push.sh "feat: my change"
#
# Then on the server (cPanel Terminal):
#   bash ~/repositories/MedzSolutions/deploy.sh
#
set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")"

MESSAGE="${1:-chore: deploy $(date '+%Y-%m-%d %H:%M')}"
BRANCH="$(git rev-parse --abbrev-ref HEAD)"

[[ -f public/hot ]] && rm -f public/hot

echo "==> Building assets"
npm run build

echo "==> Committing"
git add -A
if git diff --cached --quiet; then
    echo "Nothing new to commit."
else
    git commit -m "$MESSAGE"
fi

echo "==> Pushing to origin/$BRANCH"
git push origin "$BRANCH"

echo
echo "Pushed. Now run on the server:"
echo "  bash ~/repositories/MedzSolutions/deploy.sh"
