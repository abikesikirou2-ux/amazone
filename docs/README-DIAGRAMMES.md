# 📊 Diagrammes du Projet Mini Amazon

## 🎯 Vue d'ensemble

Ce dossier contient tous les diagrammes UML et architecturaux du projet e-commerce Mini Amazon, entièrement en français.

---

## 📂 Liste des Diagrammes

### 1. 🏗️ Diagramme de Classes
**Fichier:** [diagramme-classe.md](diagramme-classe.md)

**Lien PlantUML:** [👉 Voir le diagramme](http://www.plantuml.com/plantuml/uml/bLLjSzj047tNhxZI2YM3OjHLmYQH4aKP5gKO8Y4aY9QpYaYaYb4YcGYd0Yd1Ye2Yf3Yg4Yh5Yi6Yj7Yk8Yl9YmAYnBYoCYpDYqEYrFYsGYtHYuIYvJYwKYxLYyMYzNY-OY_PYARYBSYCTYDUYEVYFWYGXYHYYIZYJaYKbYLcYMdYNeYOfYPgYQhYRiYSjYTkYUlYVmYWnYXoYYpYZqY-rY_sYAtYBuYCvYDwYExYFyYGzYH-YI_YJAYKCYLDYMEYNG)

**Description:** Représente toutes les classes métier du projet avec leurs attributs, méthodes et relations.

**Entités principales:**
- Utilisateur, Produit, Catégorie
- Panier, ArticlePanier
- Commande, ArticleCommande
- ModeLivraison, PointRelais, AdresseLivraison
- Coupon, Avis, MouvementStock

---

### 2. 👤 Diagramme de Cas d'Utilisation
**Fichier:** [diagramme-cas-utilisation.md](diagramme-cas-utilisation.md)

**Lien PlantUML:** [👉 Voir le diagramme](http://www.plantuml.com/plantuml/uml/jLRTRjim57tNhxZoKYM2FeHPQqC8XHYXsq8qPR5ajQkh9AjsYOsuQjxxtSxfqt9fSsfQyOzyzpxxpypqvdVlm7Lxr2Gx2qxWLGNGqGUG0N0d0a9GfG0w0eaGCa5W8PW8OW9Wm3WPG0rW8CW1q1Pq0uG1G2L1k2Lq0-n0Eq0Fq0Gq0Hq0Iq0Jq0Kq0Lq0Mq0Nq0Oq0Pq0Qq0Rq0Sq0Tq0Uq0Vq0Wq0Xq0Yq0Zq10q11q12q13q14q15q16q17q18q19q20q21q22q23q24q25q26q27q28q29q30q31q32q33q34q35q36q37q38q39q40q41q42q43q44q45q46q)

**Description:** Décrit les interactions entre les acteurs (Client, Admin, Système) et le système.

**Acteurs:**
- **Client:** Navigation, achat, suivi de commandes
- **Administrateur:** Gestion produits, commandes, stocks, livraison
- **Système:** Notifications automatiques, vérifications

---

### 3. 🔄 Diagrammes de Séquence
**Fichier:** [diagramme-sequence.md](diagramme-sequence.md)

**Lien PlantUML:** [👉 Voir les séquences](http://www.plantuml.com/plantuml/uml/jLRTRjim57tNhxZoKYM2FeHPQqC8XHYXsq8qPR5ajQkh9AjsYOsuQjxxtSxfqt9fSsfQyOzyzpxxpypqvdVlm7Lxr2Gx2qxWLGNGqGUG0N0d0a9GfG0w0eaGCa5W8PW8OW9Wm3WPG0rW8CW1q1Pq0uG1G2L1k2Lq0-n0Eq0Fq0Gq0Hq0Iq0Jq0Kq0Lq0Mq0Nq0Oq0Pq0Qq0Rq0Sq0Tq0Uq0Vq0Wq0Xq0Yq0Zq10q11q12q13q14q15q16q17q18q19q20q21q22q23q24q25q26q27q28q29q30q31q32q33q34q35q36q37q38q39q40q41q42q43q44q45q46q)

**Description:** Montre les interactions chronologiques entre objets pour des scénarios clés.

**Scénarios couverts:**
1. Passage de commande complète
2. Consultation produit et ajout au panier
3. Changement de statut commande (Admin)
4. Alerte stock faible (Système)
5. Laisser un avis client

---

### 4. 🔀 Diagrammes d'Activité
**Fichier:** [diagramme-activite.md](diagramme-activite.md)

**Lien PlantUML:** [👉 Voir les activités](http://www.plantuml.com/plantuml/uml/bLLjSzj047tNhxZI2YM3OjHLmYQH4aKP5gKO8Y4aY9QpYaYaYb4YcGYd0Yd1Ye2Yf3Yg4Yh5Yi6Yj7Yk8Yl9YmAYnBYoCYpDYqEYrFYsGYtHYuIYvJYwKYxLYyMYzNY-OY_PYARYBSYCTYDUYEVYFWYGXYHYYIZYJaYKbYLcYMdYNeYOfYPgYQhYRiYSjYTkYUlYVmYWnYXoYYpYZqY-rY_sYAtYBuYCvYDwYExYFyYGzYH-YI_YJAYKCYLDYMEYNG)

**Description:** Représente les flux de travail et processus métier.

**Processus détaillés:**
1. Processus complet de commande
2. Gestion admin - traitement commande
3. Vérification stock automatique
4. Laisser un avis client
5. Recherche et filtrage produits

---

### 5. 🗄️ Modèle Conceptuel des Données (MCD)
**Fichier:** [modele-conceptuel-donnees.md](modele-conceptuel-donnees.md)

**Lien PlantUML:** [👉 Voir le MCD](http://www.plantuml.com/plantuml/uml/bLLjRziu4FxENt7HqYM3OjHLmWP5YWP5eKP5gKO8Y4aY9QoYaYaYb4YcGYd0Yd1Ye2Yf3Yg4Yh5Yi6Yj7Yk8Yl9YmAYnBYoCYpDYqEYrFYsGYtHYuIYvJYwKYxLYyMYzNY-OY_PYARYBSYCTYDUYEVYFWYGXYHYYIZYJaYKbYLcYMdYNeYOfYPgYQhYRiYSjYTkYUlYVmYWnYXoYYpYZqY-rY_sYAtYBuYCvYDwYExYFyYGzYH-YI_YJAYKCYLDYMEYNG)

**Description:** Structure complète de la base de données avec toutes les entités, relations et contraintes.

**Contenu:**
- 13 entités principales
- Cardinalités et relations
- Contraintes d'intégrité
- Index recommandés
- Types ENUM définis

---

### 6. 🏛️ Diagramme MVC
**Fichier:** [diagramme-mvc.md](diagramme-mvc.md)

**Lien PlantUML:** [👉 Voir l'architecture MVC](http://www.plantuml.com/plantuml/uml/bLLjSzj047tNhxZI2YM3OjHLmYQH4aKP5gKO8Y4aY9QpYaYaYb4YcGYd0Yd1Ye2Yf3Yg4Yh5Yi6Yj7Yk8Yl9YmAYnBYoCYpDYqEYrFYsGYtHYuIYvJYwKYxLYyMYzNY-OY_PYARYBSYCTYDUYEVYFWYGXYHYYIZYJaYKbYLcYMdYNeYOfYPgYQhYRiYSjYTkYUlYVmYWnYXoYYpYZqY-rY_sYAtYBuYCvYDwYExYFyYGzYH-YI_YJAYKCYLDYMEYNG)

**Description:** Architecture Model-View-Controller du projet Laravel.

**Composants:**
- **Models:** Eloquent ORM (13 modèles)
- **Views:** Templates Blade
- **Controllers:** 8 contrôleurs principaux
- **Services:** Logique métier
- **Events & Listeners:** Notifications automatiques

---

## 🛠️ Comment Visualiser les Diagrammes

### Option 1: PlantUML Online
1. Cliquez directement sur les liens PlantUML fournis
2. Le diagramme s'affiche dans votre navigateur
3. Vous pouvez zoomer, télécharger en PNG/SVG

### Option 2: Extension VS Code
1. Installez l'extension **PlantUML** dans VS Code
2. Ouvrez un fichier `.puml` dans le dossier `plantuml/`
3. Appuyez sur `Alt+D` pour prévisualiser

### Option 3: PlantUML Local
```bash
# Installer PlantUML
npm install -g node-plantuml

# Générer une image
puml generate docs/plantuml/diagramme-classe.puml -o output.png
```

### Option 4: Mermaid Live Editor
Pour les diagrammes Mermaid (certains fichiers contiennent les deux formats):
- Visitez: https://mermaid.live/
- Copiez le code entre ` ```mermaid ` et ` ``` `
- Visualisez en temps réel

---

## 📋 Structure des Dossiers

```
docs/
├── README-DIAGRAMMES.md          ← Vous êtes ici
├── diagramme-classe.md           ← Diagramme de classes
├── diagramme-cas-utilisation.md  ← Cas d'utilisation
├── diagramme-sequence.md         ← Séquences
├── diagramme-activite.md         ← Activités
├── modele-conceptuel-donnees.md  ← MCD
├── diagramme-mvc.md              ← Architecture MVC
└── plantuml/
    ├── diagramme-classe.puml
    ├── diagramme-sequence-commande.puml
    └── ... (autres fichiers .puml)
```

---

## 🌐 Légende des Symboles

### Diagramme de Classes
- `+` : Public
- `-` : Privé
- `#` : Protégé
- `<<PK>>` : Clé primaire
- `<<FK>>` : Clé étrangère
- `<<UNIQUE>>` : Contrainte d'unicité

### Cardinalités
- `1` : Un et un seul
- `0..1` : Zero ou un
- `*` ou `0..*` : Zero à plusieurs
- `1..*` : Un à plusieurs

### Relations
- `-->` : Association
- `--o` : Agrégation
- `--*` : Composition
- `--|>` : Héritage
- `.>` : Dépendance

---

## 📞 Support

Pour toute question sur les diagrammes:
1. Vérifiez d'abord le fichier concerné
2. Consultez le [amazone.txt](../amazone.txt) pour le contexte
3. Les diagrammes sont synchronisés avec la structure Laravel

---

## 🔄 Dernière Mise à Jour
**Date:** 15 décembre 2025  
**Version:** 1.0 - Tous les diagrammes en français avec PlantUML
