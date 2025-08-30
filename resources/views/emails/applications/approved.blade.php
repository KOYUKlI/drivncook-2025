@component('mail::message')
# 🎊 FÉLICITATIONS {{ $application->full_name }} !

Nous avons l'immense plaisir de vous annoncer que votre candidature de franchise **DrivnCook** a été **APPROUVÉE** !

@component('mail::panel', ['color' => 'success'])
🎉 **Bienvenue dans la famille DrivnCook !**  
Vous êtes maintenant officiellement partenaire de notre réseau de food trucks premium.
@endcomponent

@if($welcomeMessage)
## 💬 Message personnalisé

{{ $welcomeMessage }}
@endif

## 🚀 Prochaines étapes - Votre intégration

@component('mail::table')
| Étape | Action | Délai |
|:------|:-------|:------|
| 1️⃣ | **Signature du contrat** | Dans les 7 jours |
| 2️⃣ | **Formation initiale** | 2 semaines |
| 3️⃣ | **Équipement du food truck** | 3-4 semaines |
| 4️⃣ | **Lancement officiel** | 6 semaines |
@endcomponent

## 📋 Documents et informations

Vous allez recevoir dans les 24h :
- **Contrat de franchise** à signer
- **Guide d'intégration** complet
- **Coordonnées de votre référent** franchise
- **Planning de formation** personnalisé

## 💰 Informations financières

**Zone attribuée :** {{ $application->desired_area }}  
**Investissement total :** Selon devis personnalisé  
**Support inclus :** Formation, équipement, marketing de lancement

@if($nextSteps)
## 📝 Informations complémentaires

{{ $nextSteps }}
@endif

@component('mail::button', ['url' => 'mailto:franchise@drivncook.com', 'color' => 'success'])
📧 Contacter votre référent
@endcomponent

## 🎯 Votre succès, notre priorité

Notre équipe vous accompagnera à chaque étape pour garantir le succès de votre franchise. Vous bénéficierez de :

- 🎓 **Formation complète** (gestion, cuisine, marketing)
- 🛠️ **Support technique** permanent
- 📈 **Outils de gestion** performants
- 🤝 **Réseau de franchisés** expérimentés

@component('mail::subcopy')
**Référence :** {{ $application->id }}  
**Prochaine action :** Attendre la réception du contrat de franchise  
**Contact urgence :** franchise@drivncook.com | 01 23 45 67 89
@endcomponent

Encore une fois, félicitations et bienvenue dans l'aventure DrivnCook !

L'équipe DrivnCook  
**Direction Franchise & Développement**
@endcomponent
