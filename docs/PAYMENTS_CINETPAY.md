# CinetPay Integration — Test & Webhook (Sandbox)

This document explains how to test the CinetPay integration locally using ngrok and the sandbox keys.

1. Env variables (add to your `.env`)

CINETPAY_SITE_ID=your_sandbox_site_id
CINETPAY_API_KEY=your_sandbox_api_key
CINETPAY_WEBHOOK_SECRET=your_sandbox_webhook_secret
CINETPAY_TEST_MODE=true

2. Start your local server

```bash
# from project root
php artisan serve --host=127.0.0.1 --port=8000
```

3. Expose to the internet with ngrok (recommended)

```bash
# install ngrok and run
ngrok http 8000
```

Note the HTTPS public URL (eg `https://abc123.ngrok.io`).

4. Configure CinetPay sandbox

- In your CinetPay developer dashboard, create a sandbox site and set:
  - Return / redirection URL: `https://abc123.ngrok.io/`
  - Notify / webhook URL: `https://abc123.ngrok.io/payment/cinetpay/webhook`
  - Set the webhook secret (HMAC) and copy it into `CINETPAY_WEBHOOK_SECRET` in your `.env`.

5. Initiate a payment

- As an authenticated hospital or specialist user, open the plan checkout page:
  `GET /subscription/{plan}/checkout`
- Click the payment button — you should be redirected to the CinetPay sandbox payment selection page (Wave, Orange, MTN).

6. Webhook sample payload (simulate POST to webhook)

Use `curl` or Postman to simulate the webhook if needed:

```bash
curl -X POST 'https://abc123.ngrok.io/payment/cinetpay/webhook' \
  -H "Content-Type: application/json" \
  -H "X-CINETPAY-SIGNATURE: <computed-hmac>" \
  -d '{
    "status": "ACCEPTED",
    "transaction_id": "HP161000abcd",
    "amount": 10000,
    "currency": "XOF",
    "metadata": "{\"buyer_type\":\"specialist\", \"buyer_id\":123, \"plan_id\":1, \"transaction_ref\":\"HP161000abcd\"}"
  }'
```

- Compute `X-CINETPAY-SIGNATURE` with `HMAC-SHA256` over the raw JSON body using the `CINETPAY_WEBHOOK_SECRET`.

7. Verify results

- After a successful webhook the `payments` table should show the payment with `status=completed`.
- `TransactionLog` entries should include `activation_fee` and `specialist` entries for specialists, or a `hospital` entry for clinics.
- SuperAdmin dashboard `Revenus SaaS` should reflect the `activation_fee` + `hospital` `net_income`.

8. Troubleshooting

- If webhook signature fails, check the `X-CINETPAY-SIGNATURE` header and the secret.
- Use the logs (`storage/logs/laravel.log`) for debug messages.

---

If you want, I can run the migration now and run a simple webhook simulation locally using the test secret.