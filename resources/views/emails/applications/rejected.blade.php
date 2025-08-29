@component('mail::message')
# Merci {{ $application->full_name }}

Nous vous remercions sincèrement pour l'intérêt que vous avez porté à notre réseau de franchises **DrivnCook**.

Après un examen attentif de votre candidature, nous regrettons de vous informer que nous ne pouvons pas donner suite favorablement à votre demande à ce stade.

## 📋 Analyse de votre candidature

**Référence :** {{ $application->id }}  
**Zone souhaitée :** {{ $application->desired_area }}  
**Date d'examen :** {{ now()->format('d/m/Y') }}

@if($reason)
## 🔍 Motif de la décision

{{ $reason }}
@endif

@if($feedback)
## 💡 Retours constructifs

{{ $feedback }}
@endif

## 🔄 Nouvelles opportunités

Cette décision ne remet pas en question vos qualités entrepreneuriales. Les critères de sélection sont très stricts et dépendent de nombreux facteurs :

- **Disponibilité territoriale**
- **Correspondance profil/projet**
- **Timing de développement**
- **Capacités financières requises**

@component('mail::panel')
**Restez informé !** N'hésitez pas à candidater à nouveau dans 6 mois ou pour une autre zone géographique. Nos besoins évoluent constamment.
@endcomponent

## 📧 Restons en contact

Si vous souhaitez :
- **Feedback détaillé** sur votre candidature
- **Informations** sur de futures opportunités
- **Conseils** pour améliorer votre profil

@component('mail::button', ['url' => 'mailto:franchise@drivncook.com', 'color' => 'primary'])
📧 Nous contacter
@endcomponent

## 🌟 Alternatives possibles

Consultez nos autres opportunités :
- **Food trucks saisonniers** (investissement moindre)
- **Partenariats événementiels**
- **Opportunités dans d'autres régions**

@component('mail::subcopy')
**Référence :** {{ $application->id }}  
Merci encore pour votre confiance et votre temps accordé à notre projet.
@endcomponent

Nous vous souhaitons plein succès dans vos projets entrepreneuriaux !

Cordialement,

L'équipe DrivnCook  
**Direction Franchise & Développement**
@endcomponent
