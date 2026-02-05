# Intégration Mobile Money - Walk-in Consultations

## Configuration

### 1. Variables d'environnement

Ajoutez ces variables dans votre fichier `.env` :

```env
CINETPAY_API_KEY=votre_cle_api_cinetpay
CINETPAY_SITE_ID=votre_site_id
CINETPAY_SECRET_KEY=votre_cle_secrete
CINETPAY_BASE_URL=https://api-checkout.cinetpay.com/v2
```

### 2. Obtenir les clés CinetPay

1. Créez un compte sur [CinetPay](https://cinetpay.com)
2. Accédez à votre tableau de bord
3. Récupérez vos clés API (API Key, Site ID, Secret Key)
4. En mode test, utilisez les clés de test fournies par CinetPay

### 3. Configuration du Webhook

Pour que CinetPay puisse notifier votre application des paiements réussis :

1. **En développement local** : Utilisez [ngrok](https://ngrok.com)
   ```bash
   ngrok http 8000
   ```
   Copiez l'URL HTTPS générée (ex: `https://abc123.ngrok.io`)

2. **Dans CinetPay** :
   - Allez dans Paramètres > Webhooks
   - Ajoutez l'URL : `https://votre-domaine.com/cashier/mobile-money/webhook`
   - En local avec ngrok : `https://abc123.ngrok.io/cashier/mobile-money/webhook`

## Flux de Paiement

### Paiement en Espèces
1. Le caissier crée la consultation
2. Le patient paie en espèces
3. Le caissier valide le paiement manuellement
4. La facture est générée et le dossier envoyé à l'infirmier

### Paiement Mobile Money
1. Le caissier crée la consultation et sélectionne "Mobile Money"
2. Saisie de l'opérateur (MTN/Orange/Moov) et du numéro
3. Le système initie le paiement via CinetPay
4. Le patient reçoit une notification sur son téléphone
5. Le patient confirme le paiement
6. CinetPay envoie un webhook à l'application
7. Le système valide automatiquement le paiement et génère la facture

## Opérateurs Supportés

- **MTN Mobile Money** (Côte d'Ivoire, Bénin, etc.)
- **Orange Money** (Côte d'Ivoire)
- **Moov Money** (Côte d'Ivoire)

## Codes de Statut

### Statuts de Consultation
- `pending_payment` : En attente de paiement
- `paid` : Payé et validé
- `cancelled` : Annulé

### Codes de Réponse CinetPay
- `00` : Paiement réussi
- `201` : Transaction initiée avec succès
- Autres codes : Voir [documentation CinetPay](https://docs.cinetpay.com)

## Sécurité

- Les webhooks doivent vérifier la signature CinetPay
- Les transactions sont enregistrées avec leur ID unique
- Les montants sont en Francs CFA (XOF)
- TVA de 18% appliquée automatiquement

## Tests

### Mode Test CinetPay
Utilisez les numéros de test fournis par CinetPay pour simuler des paiements sans débiter de vrais comptes.

### Vérifier un Paiement
```php
$mobileMoneyService = new \App\Services\MobileMoneyService();
$status = $mobileMoneyService->checkPaymentStatus('WALK-123456');
```

## Dépannage

### Le webhook ne fonctionne pas
- Vérifiez que l'URL du webhook est accessible publiquement
- Vérifiez les logs : `storage/logs/laravel.log`
- Testez avec ngrok en local

### Paiement bloqué en "pending"
- Vérifiez que le webhook est bien configuré
- Vérifiez les logs CinetPay dans votre tableau de bord
- Utilisez `checkPaymentStatus()` pour vérifier manuellement

### Erreur "Invalid signature"
- Vérifiez que votre `CINETPAY_SECRET_KEY` est correct
- Implémentez la vérification de signature selon la doc CinetPay

## Support

- Documentation CinetPay : https://docs.cinetpay.com
- Support CinetPay : support@cinetpay.com
