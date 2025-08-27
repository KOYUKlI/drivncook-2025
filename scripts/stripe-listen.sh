#!/usr/bin/env bash
set -euo pipefail
if ! command -v stripe >/dev/null; then
  echo "Installe Stripe CLI: https://stripe.com/docs/stripe-cli"
  exit 1
fi
# Forward vers Sail (app) sur http://localhost
stripe listen --forward-to http://localhost/stripe/webhook
