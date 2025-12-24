# 📋 Codes PlantUML - Mini Amazon

## 🌐 Plateformes en ligne pour visualiser:

1. **PlantUML Online Server:** https://www.plantuml.com/plantuml/
2. **PlantText:** https://www.planttext.com/
3. **PlantUML QEditor:** https://plantuml-editor.kkeisuke.com/

**Instructions:** Copiez le code d'un diagramme ci-dessous et collez-le dans l'une de ces plateformes.

---

## 1️⃣ DIAGRAMME DE CLASSES

```plantuml
@startuml

!theme plain
skinparam classAttributeIconSize 0

class Utilisateur {
  +int id
  +string nom
  +string email
  +string mot_de_passe
  +string role
  +datetime date_creation
  --
  +inscrire()
  +connecter()
  +deconnecter()
}

class Produit {
  +int id
  +string nom
  +text description
  +decimal prix
  +string image
  +int categorie_id
  +int stock
  +boolean actif
  +datetime date_creation
  --
  +obtenirMoyenneNotes()
  +verifierStock()
  +diminuerStock()
}

class Categorie {
  +int id
  +string nom
  +text description
  +string slug
  --
  +obtenirProduits()
}

class Panier {
  +int id
  +int utilisateur_id
  +datetime date_creation
  --
  +ajouterArticle()
  +retirerArticle()
  +modifierQuantite()
  +obtenirTotal()
  +vider()
}

class ArticlePanier {
  +int id
  +int panier_id
  +int produit_id
  +int quantite
  +decimal prix
  --
  +obtenirSousTotal()
}

class Commande {
  +int id
  +string numero_commande
  +int utilisateur_id
  +decimal sous_total
  +decimal prix_livraison
  +decimal reduction
  +decimal total
  +string statut
  +int mode_livraison_id
  +text adresse_livraison
  +int point_relais_id
  +string numero_suivi
  +datetime date_creation
  --
  +modifierStatut()
  +calculerTotal()
  +envoyerConfirmation()
}

class ArticleCommande {
  +int id
  +int commande_id
  +int produit_id
  +int quantite
  +decimal prix
  --
  +obtenirSousTotal()
}

class ModeLivraison {
  +int id
  +string nom
  +decimal prix
  +int jours_estimes
  +boolean actif
  +text description
}

class PointRelais {
  +int id
  +string nom
  +string adresse
  +string ville
  +string code_postal
  +string telephone
  +json horaires_ouverture
  +decimal latitude
  +decimal longitude
  +boolean actif
  --
  +rechercherParCodePostal()
}

class AdresseLivraison {
  +int id
  +int utilisateur_id
  +string nom_complet
  +string telephone
  +string adresse
  +string ville
  +string code_postal
  +string pays
  +boolean par_defaut
}

class Coupon {
  +int id
  +string code
  +string type
  +decimal valeur
  +decimal montant_minimum
  +boolean livraison_gratuite
  +date date_debut
  +date date_fin
  +int utilisations_max
  +int compteur_utilisation
  +boolean actif
  --
  +valider()
  +appliquer()
}

class Avis {
  +int id
  +int produit_id
  +int utilisateur_id
  +int commande_id
  +int note
  +text commentaire
  +datetime date_creation
  --
  +estAchatVerifie()
}

class MouvementStock {
  +int id
  +int produit_id
  +int quantite
  +string type_mouvement
  +int commande_id
  +text notes
  +datetime date_creation
  --
  +verifierStockFaible()
}

class Livreur {
  +int id
  +string nom
  +string prenom
  +string email
  +string telephone
  +string ville
  +string quartier
  +boolean disponible
  +datetime date_creation
  --
  +confirmerDisponibilite()
  +accepterLivraison()
  +refuserLivraison()
}

Utilisateur "1" --> "*" Panier : possède
Utilisateur "1" --> "*" Commande : passe
Utilisateur "1" --> "*" Avis : écrit
Utilisateur "1" --> "*" AdresseLivraison : a

Commande "*" --> "0..1" Livreur : assignée_à

Categorie "1" --> "*" Produit : contient

Panier "1" --> "*" ArticlePanier : contient
ArticlePanier "*" --> "1" Produit : référence

Commande "1" --> "*" ArticleCommande : contient
Commande "*" --> "1" ModeLivraison : utilise
Commande "*" --> "0..1" PointRelais : livre_à
Commande "*" --> "0..1" Coupon : applique
ArticleCommande "*" --> "1" Produit : référence

Produit "1" --> "*" Avis : a
Produit "1" --> "*" MouvementStock : suit

Avis "*" --> "1" Utilisateur : écrit_par
Avis "*" --> "1" Commande : vérifié_par

@enduml
```

---

## 2️⃣ DIAGRAMME DE CAS D'UTILISATION

```plantuml
@startuml

left to right direction

actor "Client" as Client
actor "Administrateur" as Admin
actor "Livreur" as Livreur
actor "Système" as System

rectangle "Mini Amazon E-Commerce" {
  
  package "Cas d'utilisation Client" {
    usecase "S'inscrire / Se connecter" as UC1
    usecase "Consulter catalogue produits" as UC2
    usecase "Rechercher produits" as UC3
    usecase "Voir détails produit" as UC4
    usecase "Ajouter au panier" as UC5
    usecase "Gérer panier" as UC6
    usecase "Choisir mode livraison" as UC7
    usecase "Sélectionner point relais" as UC8
    usecase "Saisir adresse livraison" as UC9
    usecase "Appliquer coupon" as UC10
    usecase "Passer commande" as UC11
    usecase "Simuler paiement" as UC12
    usecase "Consulter historique commandes" as UC13
    usecase "Suivre livraison" as UC14
    usecase "Laisser avis produit" as UC15
    usecase "Gérer adresses livraison" as UC16
  }
  
  package "Cas d'utilisation Admin" {
    usecase "Gérer produits (CRUD)" as UC20
    usecase "Gérer catégories" as UC21
    usecase "Gérer stocks" as UC22
    usecase "Voir dashboard statistiques" as UC23
    usecase "Gérer commandes" as UC24
    usecase "Changer statut commande" as UC25
    usecase "Ajouter numéro suivi" as UC26
    usecase "Gérer coupons" as UC27
    usecase "Gérer points relais" as UC28
    usecase "Configurer modes livraison" as UC29
    usecase "Gérer utilisateurs" as UC30
    usecase "Consulter avis clients" as UC31
    usecase "Voir alertes stock" as UC32
  }
  
  package "Cas d'utilisation Livreur" {
    usecase "S'inscrire comme livreur" as UC50
    usecase "Recevoir notification commande" as UC51
    usecase "Confirmer disponibilité" as UC52
    usecase "Accepter livraison" as UC53
    usecase "Refuser livraison" as UC54
    usecase "Voir commandes assignées" as UC55
    usecase "Mettre à jour statut livraison" as UC56
  }
  
  package "Cas d'utilisation Système" {
    usecase "Envoyer email confirmation" as UC40
    usecase "Envoyer notification statut" as UC41
    usecase "Vérifier stock disponible" as UC42
    usecase "Calculer frais livraison" as UC43
    usecase "Décompter stock" as UC44
    usecase "Alerter stock faible" as UC45
    usecase "Valider coupon" as UC46
    usecase "Trouver livreur disponible" as UC47
    usecase "Envoyer email livreur" as UC48
  }
}

Client --> UC1
Client --> UC2
Client --> UC3
Client --> UC4
Client --> UC5
Client --> UC6
Client --> UC7
Client --> UC10
Client --> UC11
Client --> UC13
Client --> UC14
Client --> UC15
Client --> UC16

Admin --> UC20
Admin --> UC21
Admin --> UC22
Admin --> UC23
Admin --> UC24
Admin --> UC25
Admin --> UC26
Admin --> UC27
Admin --> UC28
Admin --> UC29
Admin --> UC30
Admin --> UC31
Admin --> UC32

UC11 .> UC7 : <<include>>
UC7 .> UC8 : <<extend>>
UC7 .> UC9 : <<extend>>
UC11 .> UC12 : <<include>>
UC15 .> UC13 : <<require>>

Livreur --> UC50
Livreur --> UC51
Livreur --> UC52
Livreur --> UC53
Livreur --> UC54
Livreur --> UC55
Livreur --> UC56

UC11 --> System
UC25 --> System
UC52 .> UC51 : <<require>>
UC53 .> UC52 : <<extend>>
UC54 .> UC52 : <<extend>>

System --> UC40
System --> UC41
System --> UC42
System --> UC43
System --> UC44
System --> UC45
System --> UC46
System --> UC47
System --> UC48

@enduml
```

---

## 3️⃣ DIAGRAMME DE SÉQUENCE - PASSAGE DE COMMANDE

```plantuml
@startuml

actor "Client" as Client
participant "Interface\nWeb" as UI
participant "Contrôleur\nPanier" as CartCtrl
participant "Contrôleur\nCommande" as OrderCtrl
participant "Contrôleur\nLivraison" as DeliveryCtrl
participant "Contrôleur\nPaiement" as PaymentCtrl
database "Base de\nDonnées" as DB
participant "Système\nÉvénements" as Event
participant "Service\nEmail" as Mail

Client -> UI: Clique "Procéder au paiement"
UI -> CartCtrl: obtenirArticlesPanier()
CartCtrl -> DB: SELECT articles_panier
DB --> CartCtrl: Articles + Totaux
CartCtrl --> UI: Affiche récapitulatif

Client -> UI: Sélectionne mode livraison

alt Livraison à domicile
    UI -> Client: Affiche formulaire adresse
    Client -> UI: Saisit/Sélectionne adresse
    UI -> DeliveryCtrl: validerAdresse(données)
    DeliveryCtrl --> UI: Adresse validée
else Livraison point relais
    Client -> UI: Saisit code postal
    UI -> DeliveryCtrl: rechercherPointsRelais(code_postal)
    DeliveryCtrl -> DB: SELECT points_relais
    DB --> DeliveryCtrl: Liste points relais
    DeliveryCtrl --> UI: Affiche points disponibles
    Client -> UI: Sélectionne point relais
end

UI -> DeliveryCtrl: calculerPrixLivraison()
DeliveryCtrl --> UI: Frais livraison

Client -> UI: Applique code coupon (optionnel)
UI -> OrderCtrl: validerCoupon(code)
OrderCtrl -> DB: SELECT coupon
DB --> OrderCtrl: Détails coupon
OrderCtrl --> UI: Réduction appliquée

UI -> Client: Affiche total final
Client -> UI: Confirme et paye

UI -> PaymentCtrl: simulerPaiement(données_commande)
PaymentCtrl --> UI: Paiement réussi (simulation)

UI -> OrderCtrl: creerCommande(panier, livraison, coupon)
OrderCtrl -> DB: BEGIN TRANSACTION
OrderCtrl -> DB: INSERT INTO commandes
OrderCtrl -> DB: INSERT INTO articles_commande
OrderCtrl -> DB: UPDATE produits (stock)
OrderCtrl -> DB: UPDATE coupon (compteur_utilisation)
OrderCtrl -> DB: DELETE articles_panier
OrderCtrl -> DB: COMMIT

OrderCtrl -> Event: déclencher(CommandeCréée)
Event -> Mail: envoyerEmail(ConfirmationCommande, client)
Event -> Mail: envoyerEmail(AlerteNouvelleCommande, admin)

Event -> DB: SELECT livreurs WHERE ville/quartier = adresse_client
DB --> Event: Liste livreurs disponibles
Event -> Mail: envoyerEmail(DemandeDisponibilité, livreur)

OrderCtrl --> UI: Commande créée (numero_commande)
UI --> Client: Page confirmation avec détails

@enduml
```

---

## 4️⃣ DIAGRAMME DE SÉQUENCE - CONFIRMATION LIVREUR

```plantuml
@startuml

actor "Livreur" as Livreur
participant "Interface\nLivreur" as LivreurUI
participant "Contrôleur\nLivraison" as DeliveryCtrl
participant "Contrôleur\nCommande" as OrderCtrl
database "Base de\nDonnées" as DB
participant "Système\nÉvénements" as Event
participant "Service\nEmail" as Mail
actor "Admin" as Admin

Livreur -> LivreurUI: Reçoit email notification
Livreur -> LivreurUI: Clique lien dans email
LivreurUI -> DeliveryCtrl: afficherDetailsCommande(commande_id)
DeliveryCtrl -> DB: SELECT commande + adresse_livraison
DB --> DeliveryCtrl: Détails commande
DeliveryCtrl --> LivreurUI: Affiche détails

LivreurUI --> Livreur: Détails commande + Adresse

alt Livreur disponible
    Livreur -> LivreurUI: Clique "Je suis disponible"
    LivreurUI -> DeliveryCtrl: confirmerDisponibilite(livreur_id, commande_id, disponible=true)
    DeliveryCtrl -> DB: UPDATE commandes SET livreur_id
    DeliveryCtrl -> DB: UPDATE livreurs SET disponible=false
    DB --> DeliveryCtrl: Mise à jour OK
    
    DeliveryCtrl -> Event: déclencher(LivreurAssigné)
    Event -> Mail: envoyerEmail(LivreurTrouvé, admin)
    Event -> Mail: envoyerEmail(LivreurAssigné, livreur)
    
    DeliveryCtrl --> LivreurUI: Livraison acceptée
    LivreurUI --> Livreur: Message "Livraison assignée avec succès"
else Livreur non disponible
    Livreur -> LivreurUI: Clique "Non disponible"
    LivreurUI -> DeliveryCtrl: confirmerDisponibilite(livreur_id, commande_id, disponible=false)
    DeliveryCtrl -> DB: LOG refus livreur
    
    DeliveryCtrl -> Event: déclencher(LivreurRefusé)
    Event -> DB: SELECT autre_livreur WHERE ville/quartier
    
    alt Autre livreur trouvé
        Event -> Mail: envoyerEmail(DemandeDisponibilité, autre_livreur)
        DeliveryCtrl --> LivreurUI: Refus enregistré
    else Aucun livreur disponible
        Event -> Mail: envoyerEmail(AucunLivreur, admin)
        Event -> OrderCtrl: changerStatut(commande_id, "en_attente_livreur")
        DeliveryCtrl --> LivreurUI: Refus enregistré
    end
    
    LivreurUI --> Livreur: Message "Refus enregistré"
end

@enduml
```

---

## 5️⃣ DIAGRAMME DE SÉQUENCE - AJOUT AU PANIER

```plantuml
@startuml

actor "Client" as Client
participant "Interface\nWeb" as UI
participant "Contrôleur\nProduit" as ProductCtrl
participant "Contrôleur\nPanier" as CartCtrl
participant "Contrôleur\nAvis" as ReviewCtrl
database "Base de\nDonnées" as DB

Client -> UI: Clique sur produit
UI -> ProductCtrl: afficher(produit_id)
ProductCtrl -> DB: SELECT produit + categorie
ProductCtrl -> DB: SELECT avis + notes
ProductCtrl -> DB: SELECT stock
DB --> ProductCtrl: Données complètes
ProductCtrl --> UI: Affiche page produit

UI --> Client: Détails + Stock + Avis + Images

Client -> UI: Sélectionne quantité
Client -> UI: Clique "Ajouter au panier"

UI -> CartCtrl: ajouterAuPanier(produit_id, quantite)
CartCtrl -> DB: SELECT stock produit

alt Stock suffisant
    DB --> CartCtrl: stock >= quantite
    CartCtrl -> DB: Vérifie panier existe
    alt Panier existe
        CartCtrl -> DB: UPDATE articles_panier (quantite)
    else Nouveau panier
        CartCtrl -> DB: INSERT panier
        CartCtrl -> DB: INSERT articles_panier
    end
    CartCtrl --> UI: Produit ajouté (succès)
    UI --> Client: Message "Ajouté au panier" + Badge mis à jour
else Stock insuffisant
    DB --> CartCtrl: stock < quantite
    CartCtrl --> UI: Erreur stock
    UI --> Client: "Stock insuffisant (disponible: X)"
end

@enduml
```

---

## 6️⃣ DIAGRAMME DE SÉQUENCE - CHANGEMENT STATUT COMMANDE (ADMIN)

```plantuml
@startuml

actor "Admin" as Admin
participant "Interface\nAdmin" as UI
participant "Contrôleur\nCommande" as OrderCtrl
database "Base de\nDonnées" as DB
participant "Système\nÉvénements" as Event
participant "Service\nEmail" as Mail
actor "Client" as Client

Admin -> UI: Accède liste commandes
UI -> OrderCtrl: index()
OrderCtrl -> DB: SELECT commandes + utilisateurs + livraison
DB --> OrderCtrl: Liste commandes
OrderCtrl --> UI: Affiche tableau commandes

Admin -> UI: Sélectionne commande
UI -> OrderCtrl: afficher(commande_id)
OrderCtrl -> DB: SELECT détails commande
DB --> OrderCtrl: Détails complets
OrderCtrl --> UI: Affiche détails

Admin -> UI: Change statut "En préparation" → "Expédiée"
Admin -> UI: Saisit numéro de suivi

UI -> OrderCtrl: modifierStatut(commande_id, statut, suivi)
OrderCtrl -> DB: UPDATE commandes SET statut, numero_suivi
DB --> OrderCtrl: Mise à jour OK

OrderCtrl -> Event: déclencher(StatutCommandeChangé)
Event -> Mail: envoyerEmail(NotificationExpédition, client)
Mail --> Client: Email "Votre colis a été expédié"

OrderCtrl --> UI: Statut mis à jour
UI --> Admin: Message confirmation

@enduml
```

---

## 7️⃣ DIAGRAMME D'ACTIVITÉ - PROCESSUS DE COMMANDE

```plantuml
@startuml

start

:Client visite le site;

if (Client connecté?) then (Non)
  :Connexion / Inscription;
else (Oui)
endif

:Parcourir catalogue;

if (Recherche produit?) then (Oui)
  :Appliquer filtres/recherche;
else (Non)
endif

:Voir détails produit;

if (Stock disponible?) then (Non)
  :Afficher rupture de stock;
  stop
else (Oui)
endif

:Ajouter au panier;

if (Continuer achats?) then (Oui)
  :Parcourir catalogue;
  detach
else (Non)
endif

:Voir panier;

if (Modifier panier?) then (Oui)
  :Modifier quantités/Supprimer;
  :Voir panier;
  detach
else (Non)
endif

:Procéder au paiement;

if (Choisir mode livraison) then (Domicile)
  if (Adresse enregistrée?) then (Oui)
    :Sélectionner adresse;
  else (Non)
    :Saisir nouvelle adresse;
  endif
  :Valider adresse;
else (Point relais)
  :Saisir code postal;
  :Rechercher points relais;
  :Afficher liste points;
  :Sélectionner point;
endif

:Calculer frais livraison;

if (Coupon disponible?) then (Oui)
  :Saisir code coupon;
  if (Coupon valide?) then (Non)
    :Erreur: coupon invalide;
  else (Oui)
    :Appliquer réduction;
  endif
else (Non)
endif

:Afficher récapitulatif;

if (Confirmer commande?) then (Non)
  :Voir panier;
  detach
else (Oui)
endif

:Page paiement simulation;
:Simuler paiement;

:== Transaction BDD ==;
:INSERT commandes;
:INSERT articles_commande;
:UPDATE stock produits;
:UPDATE coupon utilisations;
:DELETE articles_panier;
:COMMIT transaction;

:== Notification Automatique ==;

fork
  :Email confirmation client;
fork again
  :Email alerte admin;
fork again
  :Rechercher livreur disponible;
  :SELECT livreurs WHERE ville/quartier = adresse_client;
  if (Livreur trouvé?) then (Oui)
    :Email demande disponibilité livreur;
    :Attendre réponse livreur (async);
  else (Non)
    :Email admin: aucun livreur disponible;
  endif
end fork

:Page confirmation;

note right
  Le système attend la réponse
  du livreur en arrière-plan
end note

stop

@enduml
```

---

## 8️⃣ DIAGRAMME D'ACTIVITÉ - RÉPONSE LIVREUR

```plantuml
@startuml

title Processus de Confirmation Livreur

start

:Livreur reçoit email notification;

:Ouvre lien dans email;

:Affiche détails commande;
note right
  - Numéro commande
  - Adresse livraison
  - Ville/Quartier
  - Distance estimée
  - Montant livraison
end note

if (Livreur disponible?) then (Oui)
  :Clique "Je suis disponible";
  
  :Système assigne commande au livreur;
  
  :UPDATE commandes SET livreur_id;
  :UPDATE livreurs SET disponible = false;
  
  fork
    :Email confirmation livreur;
    :"Livraison assignée";
  fork again
    :Email notification admin;
    :"Livreur trouvé pour commande #XXX";
  end fork
  
  :Afficher message succès;
  :"Livraison assignée avec succès";
  
  stop
  
else (Non)
  :Clique "Non disponible";
  
  :Enregistrer refus;
  :LOG refus dans système;
  
  :Rechercher autre livreur;
  
  if (Autre livreur disponible?) then (Oui)
    :Envoyer email autre livreur;
    :Attendre nouvelle réponse;
    detach
  else (Non)
    :Alerter admin;
    fork
      :Email admin urgent;
      :"Aucun livreur disponible";
    fork again
      :Changer statut commande;
      :"En attente livreur";
    end fork
    stop
  endif
endif

@enduml
```

---

## 9️⃣ DIAGRAMME MCD (MODÈLE CONCEPTUEL DES DONNÉES)

```plantuml
@startuml

entity Utilisateur {
  * id : INT <<PK>>
  --
  nom : VARCHAR(255)
  email : VARCHAR(255) <<UNIQUE>>
  mot_de_passe : VARCHAR(255)
  role : ENUM('client','admin')
  email_verifie_le : TIMESTAMP
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity Categorie {
  * id : INT <<PK>>
  --
  nom : VARCHAR(255)
  description : TEXT
  slug : VARCHAR(255) <<UNIQUE>>
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity Produit {
  * id : INT <<PK>>
  --
  nom : VARCHAR(255)
  description : TEXT
  prix : DECIMAL(10,2)
  image : VARCHAR(255)
  categorie_id : INT <<FK>>
  stock : INT
  actif : BOOLEAN
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity Panier {
  * id : INT <<PK>>
  --
  utilisateur_id : INT <<FK>>
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity ArticlePanier {
  * id : INT <<PK>>
  --
  panier_id : INT <<FK>>
  produit_id : INT <<FK>>
  quantite : INT
  prix : DECIMAL(10,2)
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity Commande {
  * id : INT <<PK>>
  --
  numero_commande : VARCHAR(50) <<UNIQUE>>
  utilisateur_id : INT <<FK>>
  sous_total : DECIMAL(10,2)
  prix_livraison : DECIMAL(10,2)
  reduction : DECIMAL(10,2)
  total : DECIMAL(10,2)
  statut : ENUM
  mode_livraison_id : INT <<FK>>
  adresse_livraison : TEXT
  ville_livraison : VARCHAR(100)
  quartier_livraison : VARCHAR(100)
  point_relais_id : INT <<FK>>
  livreur_id : INT <<FK>>
  numero_suivi : VARCHAR(100)
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity ArticleCommande {
  * id : INT <<PK>>
  --
  commande_id : INT <<FK>>
  produit_id : INT <<FK>>
  quantite : INT
  prix : DECIMAL(10,2)
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity ModeLivraison {
  * id : INT <<PK>>
  --
  nom : VARCHAR(255)
  prix : DECIMAL(10,2)
  jours_estimes : INT
  actif : BOOLEAN
  description : TEXT
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity PointRelais {
  * id : INT <<PK>>
  --
  nom : VARCHAR(255)
  adresse : VARCHAR(255)
  ville : VARCHAR(100)
  code_postal : VARCHAR(10)
  telephone : VARCHAR(20)
  horaires_ouverture : JSON
  latitude : DECIMAL(10,8)
  longitude : DECIMAL(11,8)
  actif : BOOLEAN
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity AdresseLivraison {
  * id : INT <<PK>>
  --
  utilisateur_id : INT <<FK>>
  nom_complet : VARCHAR(255)
  telephone : VARCHAR(20)
  adresse : VARCHAR(255)
  ville : VARCHAR(100)
  code_postal : VARCHAR(10)
  pays : VARCHAR(100)
  par_defaut : BOOLEAN
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity Coupon {
  * id : INT <<PK>>
  --
  code : VARCHAR(50) <<UNIQUE>>
  type : ENUM('pourcentage','montant_fixe')
  valeur : DECIMAL(10,2)
  montant_minimum : DECIMAL(10,2)
  livraison_gratuite : BOOLEAN
  date_debut : DATE
  date_fin : DATE
  utilisations_max : INT
  compteur_utilisation : INT
  actif : BOOLEAN
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity Avis {
  * id : INT <<PK>>
  --
  produit_id : INT <<FK>>
  utilisateur_id : INT <<FK>>
  commande_id : INT <<FK>>
  note : INT
  commentaire : TEXT
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

entity MouvementStock {
  * id : INT <<PK>>
  --
  produit_id : INT <<FK>>
  quantite : INT
  type_mouvement : ENUM('entree','sortie','ajustement')
  commande_id : INT <<FK>>
  notes : TEXT
  date_creation : TIMESTAMP
}

entity Livreur {
  * id : INT <<PK>>
  --
  nom : VARCHAR(100)
  prenom : VARCHAR(100)
  email : VARCHAR(255) <<UNIQUE>>
  telephone : VARCHAR(20)
  ville : VARCHAR(100)
  quartier : VARCHAR(100)
  disponible : BOOLEAN
  date_creation : TIMESTAMP
  date_modification : TIMESTAMP
}

Utilisateur ||--o{ Panier
Utilisateur ||--o{ Commande
Utilisateur ||--o{ AdresseLivraison
Utilisateur ||--o{ Avis

Categorie ||--o{ Produit

Produit ||--o{ ArticlePanier
Produit ||--o{ ArticleCommande
Produit ||--o{ Avis
Produit ||--o{ MouvementStock

Panier ||--o{ ArticlePanier

Commande ||--o{ ArticleCommande
Commande }o--|| ModeLivraison
Commande }o--o| PointRelais
Commande }o--o| Coupon
Commande }o--o| Livreur

Avis }o--|| Commande

@enduml
```

---

## 📝 Instructions d'utilisation:

### Méthode 1: Plateforme en ligne
1. **Choisir une plateforme:**
   - https://www.plantuml.com/plantuml/ (Officiel)
   - https://www.planttext.com/ (Alternative simple)
   - https://plantuml-editor.kkeisuke.com/ (Éditeur visuel)

2. **Copier le code** d'un diagramme ci-dessus (tout entre @startuml et @enduml)

3. **Coller le code** dans l'éditeur de la plateforme

4. **Le diagramme s'affiche** automatiquement

5. **Télécharger** en PNG, SVG ou PDF selon vos besoins

### Méthode 2: Extension VS Code (Recommandé)
1. Installer l'extension **PlantUML** dans VS Code
2. Créer un fichier `.puml` et coller le code
3. Appuyer sur `Alt+D` pour prévisualiser
4. Exporter en cliquant droit → Export Current Diagram

### Méthode 3: Application de bureau
- Télécharger **PlantUML QEditor** sur GitHub
- Interface graphique avec prévisualisation en temps réel
