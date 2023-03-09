# Arbre des cours

Ce code a pour but de présenter un arbre des cours avec leurs rattachements.
Il peut s'utiliser soit comme un filtre Moodle, soit comme un widget Javascript.

Sa particularité est de fusionner en un seul arbre virtuel :

*  la *hiérarchie des catégories de cours* (standard Moodle),
correspondant aux quatre premiers niveaux de l'arbre, 
*  et l'arbre du ROF (spécifique UP1), correspondant aux niveaux inférieurs de l'arbre.

De plus, le widget permet de choisir la racine relative de l'affichage (par exemple, limité à une composante unique, niveau 3 de l'arbre complet).

# Consignes

## Règles de rattachement

Un cours Moodle peut être rattaché à l'arbre virtuel de quatre manières, donnant les types de noeud suivants :
 1.  *nodeRofMain*, rattachement ROF principal, **unique**, via la métadonnée `up1rofpathid`,
de la forme */15/UP1-PROG32862/UP1-PROG32880/UP1-C32881*,
 2.  *nodeRofSecondary*, rattachements ROF secondaires, **multiples** (ou vide...), valeurs suivantes du champ `up1rofpathid`,
 3.  *nodeCatBis*, catégories de cours supplémentaires hors ROF, **multiples**,
(métadonnée `up1categoriesbis` multivaluée), indiquant 0 à N entiers,
identifiants des catégories concernées : destinées aux espaces de cours hors ROF.
 4.  *nodeCatRof*, catégories de cours supplémentaires pour les rattachements ROF (métadonnée `up1categoriesbisrof`),
mêmes contraintes : destinées aux espaces de cours hybrides, ie issus du ROF (rofpathid présent) ET nécessitant un affichage secondaire

Contraintes :

*  Les quatres premiers niveaux de l'arbre sont exclusivement des catégories Moodle.
*  Les niveaux inférieurs (5 et plus) sont exclusivement des cours et des conteneurs ROF.
*  En conséquence, les catégories de rattachement supplémentaires (R3, R4) doivent être obligatoirement de niveau 4.


## Règles d'affichage

Ces règles d'affichage sont prévues pour un arbre dépliable, affiché niveau par niveau.
Le widget doit permettre de définir la racine d'affichage, à n'importe quel niveau.

 1.  Un noeud de l'arbre peut être de 3 types :
    - un espace de cours Moodle "simple" (feuille de l'arbre)
    - un "conteneur" dépliable, contenant des descendants cours Moodle : catégorie de cours Moodle OU conteneur ROF (ex. un diplôme)
    - un hybride : ex. un semestre ayant un cours "global" rattaché directement ET des cours descendants
 2.  Dans le cas d'un noeud hybride, l'arbre doit présenter un niveau supplémentaire, avec
     - en premier lieu la liste des rattachements directs (*nommés selon les Cours-Moodle*),
     - et un niveau à déplier pour les rattachements descendants (*nommé selon le nom ROF*).
 3.  Ne sont affichés que les noeuds qui "contiennent" réellement des cours (tous types de rattachements confondus),
 4.  Un paramètre "visible" doit permettre de choisir si on considère tous les cours (visible=0)
     ou seulement les cours ouverts aux étudiants (visible=1) (M1898)
 5.  De préférence, les semestres doivent être listés dans l'ordre numérique. (M1879)
 6.  Si un noeud ne comporte que des rattachements secondaires, il est tout de même affiché (M1880)


# Technique

## Test

Le service peut être testé directement à l'adresse `/local/mwscoursetree/widget-demo.php?node=/cat0`.
Il suffit de modifier le paramètre `node` pour obtenir un sous-arbre.

## Chemins

Les identifiants de noeuds sont des "pseudo-chemins" constitués d'une part de la référence à la catégorie (pour les 4 premiers niveaux de l'arbre), et d'autre part de la référence au chemin ROF (pour les niveaux inférieurs).

Ex. : **/cat2060/02:UP1-PROG26751/UP1-PROG33835/UP1-C33843** 
 1.  **2060** est l'identifiant de catégorie, interne à Moodle (Année 2012-2013 ► Paris 1 ► 02-Economie ► Masters)
 2.  **02:UP1-PROG26751/UP1-PROG33835/UP1-C33843** est le chemin des éléments pédagogiques du ROF, à partir de la composante (02-Économie > Master 1 Economie théorique & empirique > Semestre 1 > UE2)

Note : l'indication de composante est redondante, car elle fait partie à la fois de l'arbre des catégories et de l'arbre du ROF.


## Filtre Moodle

Pour appeler ce widget dans un bloc html, on peut utiliser un filtre (`filter_courseup1`).
Il faut ajouter une balise `[coursetree node="..."]` dans le bloc de texte.

Par exemple, dans un bloc HTML :

```
    <h2>Tous les cours</h2>
    [coursetree node="/cat0"]
        
    <h2>Ce diplôme</h2>
    [coursetree node="/cat2073/05:UP1-PROG33939"]
```

Il faut préalablement que le filtre soit activé par l'administrateur via
*► Administration du site ► Plugins ► Filtres ► Gestion des filtres :  Widgets des cours*


## Intégration html (hors Moodle)

Techniquement, nous utilisons le widget **jqTree**, légèrement adapté :
<http://mbraak.github.com/jqTree/>

Page de démo : 
<https://moodle-test.univ-paris1.fr/local/mwscoursetree/widget-demo.php>

Par la méthode "brute", il faut ajouter les deux lignes :

```
    <script type="text/javascript" src="https://moodle-test.univ-paris1.fr/local/mwscoursetree/widget.js"></script>
    <div class="coursetree" data-root="/cat0"></div>
```

Le paramètre **data-root** peut être modifié pour désigner n'importe quel noeud comme racine relative de l'arbre affiché.  
Sur la page widget-demo, pour noeud (dépliable) de l'arbre, c'est la valeur indiquée avant le nom, entre crochets.

## Style graphique

Le style de rendu est paramétrable dans le fichier CSS propre au plugin : 
`local/mwscoursetree/assets/jqtree.css`, section Custom à la fin du fichier.

Schématiquement, les lignes correspondant à un cours Moodle sont composées de 3 champs "tabulés", ex.

```
    <span class="jqtree-common">
    <span class="coursetree-name">Nom du cours</span>
    <span class="coursetree-teachers">Enseignants</span>
    <span class="cousetree-icons">Images et liens des icônes</span>
    </span>
```

alors que les lignes correspondant à un noeud dépliable sont composées d'un seul champ, ex. 

```
    <span class="jqtree-common jqtree-title">
    <span class="coursetree-dir">Nom du noeud</span>
    </span>
```

Dans la documentation de jqTree, l'exemple le plus proche de notre cas est 
<http://mbraak.github.com/jqTree/examples/example5.html>
