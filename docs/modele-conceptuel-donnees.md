# Modèle Conceptuel des Données (MCD) - Mini Amazon

## 🔗 Visualiser le diagramme
**Lien PlantUML:** [Voir le MCD complet](http://www.plantuml.com/plantuml/uml/bLLjRziu4FxENt7HqYM3OjHLmWP5YWP5eKP5gKO8Y4aY9QoYaYaYb4YcGYd0Yd1Ye2Yf3Yg4Yh5Yi6Yj7Yk8Yl9YmAYnBYoCYpDYqEYrFYsGYtHYuIYvJYwKYxLYyMYzNY-OY_PYARYBSYCTYDUYEVYFWYGXYHYYIZYJaYKbYLcYMdYNeYOfYPgYQhYRiYSjYTkYUlYVmYWnYXoYYpYZqY-rY_sYAtYBuYCvYDwYExYFyYGzYH-YI_YJAYKCYLDYMEYNG)

## Diagramme Entité-Association

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
  point_relais_id : INT <<FK>>
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

' Relations
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

Avis }o--|| Commande

@enduml
```

```mermaid
erDiagram
    USER ||--o{ CART : "possède"
    USER ||--o{ ORDER : "passe"
    USER ||--o{ DELIVERY_ADDRESS : "a"
    USER ||--o{ REVIEW : "écrit"
    
    CATEGORY ||--o{ PRODUCT : "contient"
    
    PRODUCT ||--o{ CART_ITEM : "inclus dans"
    PRODUCT ||--o{ ORDER_ITEM : "commandé dans"
    PRODUCT ||--o{ REVIEW : "reçoit"
    PRODUCT ||--o{ STOCK_MOVEMENT : "suit"
    
    CART ||--o{ CART_ITEM : "contient"
    
    ORDER ||--o{ ORDER_ITEM : "contient"
    ORDER }o--|| DELIVERY_METHOD : "utilise"
    ORDER }o--o| RELAY_POINT : "livre à"
    ORDER }o--o| COUPON : "applique"
    
    REVIEW }o--|| ORDER : "vérifié par"

    USER {
        int id PK
        string name
        string email UK
        string password
        enum role
        timestamp email_verified_at
        timestamp created_at
        timestamp updated_at
    }

    CATEGORY {
        int id PK
        string name
        text description
        string slug UK
        timestamp created_at
        timestamp updated_at
    }

    PRODUCT {
        int id PK
        string name
        text description
        decimal price
        string image
        int category_id FK
        int stock
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    CART {
        int id PK
        int user_id FK
        timestamp created_at
        timestamp updated_at
    }

    CART_ITEM {
        int id PK
        int cart_id FK
        int product_id FK
        int quantity
        decimal price
        timestamp created_at
        timestamp updated_at
    }

    ORDER {
        int id PK
        string order_number UK
        int user_id FK
        decimal subtotal
        decimal delivery_price
        decimal discount
        decimal total
        enum status
        int delivery_method_id FK
        text delivery_address
        int relay_point_id FK
        string tracking_number
        timestamp created_at
        timestamp updated_at
    }

    ORDER_ITEM {
        int id PK
        int order_id FK
        int product_id FK
        int quantity
        decimal price
        timestamp created_at
        timestamp updated_at
    }

    DELIVERY_METHOD {
        int id PK
        string name
        decimal price
        int estimated_days
        boolean is_active
        text description
        timestamp created_at
        timestamp updated_at
    }

    RELAY_POINT {
        int id PK
        string name
        string address
        string city
        string postal_code
        string phone
        json opening_hours
        decimal latitude
        decimal longitude
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    DELIVERY_ADDRESS {
        int id PK
        int user_id FK
        string full_name
        string phone
        string address
        string city
        string postal_code
        string country
        boolean is_default
        timestamp created_at
        timestamp updated_at
    }

    COUPON {
        int id PK
        string code UK
        enum type
        decimal value
        decimal min_amount
        boolean free_shipping
        date valid_from
        date valid_until
        int max_uses
        int used_count
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    REVIEW {
        int id PK
        int product_id FK
        int user_id FK
        int order_id FK
        int rating
        text comment
        timestamp created_at
        timestamp updated_at
    }

    STOCK_MOVEMENT {
        int id PK
        int product_id FK
        int quantity
        enum movement_type
        int order_id FK
        text notes
        timestamp created_at
    }
```

---

## Cardinalités Détaillées

### Relations Principales

| Entité 1 | Cardinalité | Relation | Cardinalité | Entité 2 |
|----------|-------------|----------|-------------|----------|
| **USER** | 1,1 | possède | 0,N | **CART** |
| **USER** | 1,1 | passe | 0,N | **ORDER** |
| **USER** | 1,1 | a | 0,N | **DELIVERY_ADDRESS** |
| **USER** | 1,1 | écrit | 0,N | **REVIEW** |
| **CATEGORY** | 1,1 | contient | 0,N | **PRODUCT** |
| **PRODUCT** | 1,1 | inclus dans | 0,N | **CART_ITEM** |
| **PRODUCT** | 1,1 | commandé dans | 0,N | **ORDER_ITEM** |
| **PRODUCT** | 1,1 | reçoit | 0,N | **REVIEW** |
| **PRODUCT** | 1,1 | suit | 0,N | **STOCK_MOVEMENT** |
| **CART** | 1,1 | contient | 0,N | **CART_ITEM** |
| **ORDER** | 1,1 | contient | 1,N | **ORDER_ITEM** |
| **ORDER** | 0,N | utilise | 1,1 | **DELIVERY_METHOD** |
| **ORDER** | 0,N | livre à | 0,1 | **RELAY_POINT** |
| **ORDER** | 0,N | applique | 0,1 | **COUPON** |
| **REVIEW** | 0,N | vérifié par | 1,1 | **ORDER** |

---

## Contraintes d'Intégrité

### Contraintes Référentielles
- `product.category_id` → `category.id` (ON DELETE RESTRICT)
- `cart.user_id` → `user.id` (ON DELETE CASCADE)
- `cart_item.cart_id` → `cart.id` (ON DELETE CASCADE)
- `cart_item.product_id` → `product.id` (ON DELETE CASCADE)
- `order.user_id` → `user.id` (ON DELETE RESTRICT)
- `order.delivery_method_id` → `delivery_method.id` (ON DELETE RESTRICT)
- `order.relay_point_id` → `relay_point.id` (ON DELETE SET NULL)
- `order_item.order_id` → `order.id` (ON DELETE CASCADE)
- `order_item.product_id` → `product.id` (ON DELETE RESTRICT)
- `delivery_address.user_id` → `user.id` (ON DELETE CASCADE)
- `review.product_id` → `product.id` (ON DELETE CASCADE)
- `review.user_id` → `user.id` (ON DELETE CASCADE)
- `review.order_id` → `order.id` (ON DELETE CASCADE)
- `stock_movement.product_id` → `product.id` (ON DELETE CASCADE)

### Contraintes Métier
1. **Stock** : `product.stock >= 0`
2. **Prix** : `product.price > 0`
3. **Quantité** : `cart_item.quantity > 0` AND `order_item.quantity > 0`
4. **Note** : `review.rating BETWEEN 1 AND 5`
5. **Statut commande** : `order.status IN ('pending', 'confirmed', 'preparing', 'shipped', 'in_delivery', 'delivered', 'cancelled')`
6. **Type coupon** : `coupon.type IN ('percentage', 'fixed_amount')`
7. **Validité coupon** : `coupon.valid_from < coupon.valid_until`
8. **Usage coupon** : `coupon.used_count <= coupon.max_uses`
9. **Email unique** : `user.email` UNIQUE
10. **Code coupon unique** : `coupon.code` UNIQUE
11. **Numéro commande unique** : `order.order_number` UNIQUE
12. **Adresse par défaut** : Un seul `delivery_address.is_default = true` par utilisateur
13. **Livraison mutuelle exclusive** : Si `order.relay_point_id IS NOT NULL` alors `delivery_address` contient adresse point relais, sinon adresse client

### Règles de Calcul
- `order.subtotal` = Σ(`order_item.price` × `order_item.quantity`)
- `order.discount` = Calculé selon `coupon.type` et `coupon.value`
- `order.total` = `subtotal` + `delivery_price` - `discount`
- Moyenne rating produit = AVG(`review.rating`) WHERE `product_id` = X

---

## Index Recommandés

```sql
-- Performance recherche
CREATE INDEX idx_product_name ON product(name);
CREATE INDEX idx_product_category ON product(category_id);
CREATE INDEX idx_product_stock ON product(stock);
CREATE INDEX idx_product_active ON product(is_active);

-- Performance commandes
CREATE INDEX idx_order_user ON order(user_id);
CREATE INDEX idx_order_status ON order(status);
CREATE INDEX idx_order_created ON order(created_at);
CREATE INDEX idx_order_number ON order(order_number);

-- Performance panier
CREATE INDEX idx_cart_user ON cart(user_id);
CREATE INDEX idx_cart_item_cart ON cart_item(cart_id);

-- Performance avis
CREATE INDEX idx_review_product ON review(product_id);
CREATE INDEX idx_review_rating ON review(rating);

-- Performance livraison
CREATE INDEX idx_relay_postal ON relay_point(postal_code);
CREATE INDEX idx_relay_active ON relay_point(is_active);

-- Performance coupons
CREATE INDEX idx_coupon_code ON coupon(code);
CREATE INDEX idx_coupon_valid ON coupon(valid_from, valid_until);
```

---

## Types ENUM

### order.status
- `pending` : En attente
- `confirmed` : Confirmée
- `preparing` : En préparation
- `shipped` : Expédiée
- `in_delivery` : En cours de livraison
- `delivered` : Livrée
- `cancelled` : Annulée

### coupon.type
- `percentage` : Pourcentage (ex: 10%)
- `fixed_amount` : Montant fixe (ex: 5€)

### user.role
- `customer` : Client
- `admin` : Administrateur

### stock_movement.movement_type
- `in` : Entrée stock (réapprovisionnement)
- `out` : Sortie stock (vente)
- `adjustment` : Ajustement inventaire
