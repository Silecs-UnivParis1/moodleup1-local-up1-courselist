# Liste des cours

La liste des cours permet d'afficher (sous forme de tableaux ou de listes) des cours vérifiant certains critères de recherche.

Une page de démonstration du widget associé est accessible : 
<https://moodle-test.univ-paris1.fr/local/widget_courselist/courselist-demo.php>


## Filtre Moodle

La syntaxe du filtre a deux variantes de présentation : `[coursetable ...]` et `[courselist ...]`, acceptant les mêmes paramètres.
Dans chaque cas, des paramètres sont attendus entre les crochets.

Exemple :
Pour sélectionner les cours sous un nœud du ROF récursivement, il faut ajouter le critère `node="..."`.
Par exemple

    [courselist node="/cat1442/02"]
    [coursetable node="/cat1442/02/UP1-PROG39308"]
    
Cf <https://moodle-test.univ-paris1.fr/local/mwscoursetree/coursetable-demo.php?node=/cat1442/02/UP1-PROG39308>.

Plus généralement, voici la liste des paramètres disponibles :

* `search` permettant une recherche sur le nom complet et la description (par exemple, `droit -positif`)
* `node` un nœud du ROF
* `startdateafter` et `startdatebefore`, au format AAAA-MM-JJ, ex. `startdatebefore="2013-12-31"`
* `visible` = 1 (seulement les cours visibles = ouverts) ou 0 (tous les cours) ; 0 par défaut
* `category` et `topcategory`, indiquant des identifiants de catégorie, en version simple et récursive respectivement
* `enrolled` le nom complet de l'utilisateur inscrit au cours, en recherche partielle
* `enrolledexact` le login (username) exact de l'utilisateur inscrit au cours
* `enrolledroles` une liste de rôles considérés pour l'inscription ci-dessus, au format `"1,2,3"` ; par défaut, 3, c'est-à-dire enseignant.
* `up1...` toutes les métadonnées existantes

Les valeurs de paramètres peuvent être entre guillemets, facultativement.

Comme pour l'arbre dépliant, il faut préalablement que le filtre soit activé par l'administrateur via
*► Administration du site ► Plugins ► Filtres ► Gestion des filtres : Widget des cours*


### Paramètres complémentaires pour `coursetable`

Certains paramètres ne sont pris en compte que par `coursetable` car ils configurent le plugin JS dataTables :

* `table-bFilter` = true|false (par défaut, false)
* `table-bPaginate` = true|false (par défaut, false)

La liste complète est dans la [documentation de DataTables](http://datatables.net/usage/features), onglets "features", "options", etc.

```
    <p>
      Un tableau de tous les cours visibles dont le titre ou la description contient "UP1",
      avec une zone de recherche et une pagination de 2 cours par page.
    </p>
    [coursetable search="UP1" visible=1 table-bFilter=true table-iDisplayLength=2 table-bPaginate=true]
```

 
## Intégration javascript (hors Moodle)

Pour une intégration hors Moodle, il faut inclure ce code javascript :

```
    <script src="/chemin/vers/js/courselist.js" />
    <script>
        jQuery("#widget-courselist").courselist({topcategory: 5});
    </script>
```

Le code `courselist.js` peut être placé n'importe où, hors de Moodle.
Dans une instance déployée de Moodle-UP1, c'est l'url relative `/local/widget_courselist/courselist.js`, par exemple
<https://moodle-test.univ-paris1.fr/local/widget_courselist/courselist.js>

Dans le code ci-dessus, la première ligne charge la bibliothèque JS
et ses dépendances. La seconde balise "script" insère le tableau de cours
dans l'élément HTML d'id "widget-courselist".


La fonction jQuery **courselist()** accepte en paramètre optionnel
une structure de données qui configure le formulaire :

```
    {
        // Display courses in a "table" or in a "list"
        format: "table",
    
        // search terms applied to the fullname and the description of each course
        // use -something to exclude words
        search: "",
    
        // Date as YYYY-MM-DD
        startdateafter: "",
        startdatebefore: "",
    
        // Date as YYYY-MM-DD
        createdafter: "",
        createdbefore: "",
    
        // limit the search to courses directly under this category ID
        category: 2,
    
        // limit the search to courses under this category ID (recursively)
        topcategory: 22,
    
        // limit the search to courses under this ROF path (recursively)
        node: "/02/UP1-PROG39308/UP1-PROG24870",
    
        // search on a part of the full name of persons enrolled in the course 
        enrolled: "Dupond",
    
        // search the exact username of persons enrolled in the course 
        enrolledexact: "dupond_a",
    
        // for the previous criteria (enrolled, enrolledexact), only consider the following roles
        // (defaults to "3", AKA "teacher")
        enrolledroles: [3],
    
        // criteria on custom course fields
        "custom": {
            up1code: "xyz",
            up1demandeurid: 4
        }
        
        // Any parameter for the plugin DataTables
        table: {
            // See http://datatables.net/usage/features
        }
    }
```

Exemple complet (HTML + JS, avec configuration du formulaire) :

```
    <h2>Cours de la catégorie 5</h2>
    <div id="widget-courselist-cat"></div>
    <h2>Cours intéressants</h2>
    <div id="widget-courselist-misc"></div>
    
    <script src="/js/courselist.js" />
    <script>
      jQuery("#widget-courselist-cat").courselist({
        format: "table",
        topcategory: 4
      });
      jQuery("#widget-courselist-misc").courselist({
        format: "list",
        enrolled: "Dupond",
        "custom": {
            up1demandeurid: 4
        },
        table: {
            bPaginate: false,
            bFilter: false
        }
      });
    </script>
```
