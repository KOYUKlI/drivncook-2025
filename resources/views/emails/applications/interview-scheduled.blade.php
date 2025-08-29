@component('mail::message')
# 🎉 Excellente nouvelle {{ $application->full_name }} !

Votre candidature de franchise **DrivnCook** a passé avec succès l'étape de pré-qualification !

Nous sommes ravis de vous annoncer que votre profil correspond à nos critères et nous souhaitons maintenant vous rencontrer lors d'un entretien.

## 📅 Votre entretien est programmé

**Date et heure :** {{ $interviewDate }}

@if($interviewDetails)
**Détails complémentaires :**
{{ $interviewDetails }}
@endif

## 📋 Que devez-vous préparer ?

@component('mail::panel')
**Documents à apporter :**
- Une pièce d'identité
- Vos justificatifs financiers complets
- Vos questions sur le concept DrivnCook

**Sujets qui seront abordés :**
- Votre motivation et votre projet
- Les modalités d'investissement
- Le territoire souhaité
- Le planning de lancement
@endcomponent

## 💼 Déroulement de l'entretien

L'entretien durera environ **1h30** et se déroulera en présence de notre responsable développement franchise. Ce sera l'occasion d'échanger sur votre projet et de répondre à toutes vos questions.

@component('mail::button', ['url' => route('public.franchise-info'), 'color' => 'success'])
📖 Préparer mon entretien
@endcomponent

## 📞 Contact

Si vous avez des questions ou un empêchement, n'hésitez pas à nous contacter :

**Email :** franchise@drivncook.com  
**Téléphone :** 01 23 45 67 89

@component('mail::subcopy')
**Référence candidature :** {{ $application->id }}  
Cet entretien est une étape cruciale pour valider votre projet de franchise !
@endcomponent

Nous avons hâte de vous rencontrer !

L'équipe DrivnCook  
**Franchise & Développement**
@endcomponent
