# wild-series

Quête N°4 : integration de webpack

C’est le moment de donner de la couleur et de la vie à ton Wild Series ! Grâce à Webpack et Bootstrap.
•Installe et configure Webpack Encore pour ton projet Wild Series.
•Charge SASS loader et JQuery.
•Intègre le framework Bootstrap (en suivant la ressource).
•Créé une navbar dans un fichier navbar.html.twig et inclus ce fichier dans une balise <header> de ton fichier base.html.twig, afin que celle-ci s’affiche sur toutes les pages. Ajoute un simple lien permettant de revenir sur la page d’accueil (Utilise une navbar de bootstrap)
•Surcharge les variables de couleurs primaires et secondaires en te basant sur ton styleguide.
•Modifie le projet pour que tous tes assets (JS, CSS) soient dans le dossier assets, et lance le build de Webpack Encore.
•Modifie tes appels aux assets pour que ton site utilise ceux générés dans ton dossier build/.
•Héberge le projet sur Github avec tous les fichiers non suivis dans le .gitignore.


Quête N°5 : Crée ta propre route

Crée une route wild/show/{slug} permettant de charger une vue affichant le slug dynamiquement sous forme de titre, dans une balise <h1>.

Le slug en question ne devra contenir que des caractères entre a et z (minuscules uniquement), des chiffres de 0 à 9 ou des tirets -.

La route devra être reliée à une méthode show du WildController.

Avant d'appeler la vue Twig, cette méthode devra remplacer tous les tirets du slug par des espaces, puis passer la première lettre de chaque mot en majuscule (regarde la fonction ucwords) pour avoir un titre de série lisible. Tu trouveras des exemples dans les critères de validation.

Si aucun slug n'est fourni, il faudra afficher “Aucune série sélectionnée, veuillez choisir une série” dans la balise h1.


Quête N°6 : Crée les entités Category et Program (sans liaison)

Crée deux entités Category et Program.

Category:

•id : integer (Clé primaire)
•name : string (Obligatoire, valeur max 100).

Program:
•id : integer (Clé primaire)
•title : string (Obligatoire)
•summary : text (Obligatoire)
•poster : string (Facultative)

Pour le moment ces deux entités ne sont pas liées.

Tu dois également mettre à jour ta base de données en conséquence.


Quête N°7 : Crée la relation ManyToOne

Ce challenge sera très simple, car tu as besoin des quêtes suivantes pour mettre en place des choses plus complexes. Il s'agira uniquement de contrôler visuellement le code de la classe Program. Cette dernière doit être conforme à ce qui est expliqué dans la quête. C’est-à-dire, une classe Program.php qui contient une propriété category paramétrée comme il se doit dans ses annotations avec les getter et setter associés.


Quête N°8 : FindBy() : récupérer plusieurs objets avec des filtres

Tu as utilisé pour le moment les méthodes findAll() et findOneBy().

Il est temps pour toi de mettre en pratique la méthode findBy(). Le principe reste identique à la méthode findOneBy(), mais au lieu de récupérer strictement un seul tuple, tu en récupères plusieurs liés à une catégorie donnée. De plus, n’oublie pas, tu peux ajouter d’autres paramètres à cette méthode, très utiles pour trier ou limiter tes résultats.

1.Crée une nouvelle méthode dans la classe WildController nommée showByCategory(string $categoryName). Celle-ci doit prendre prendre en paramètre le nom d’une catégorie de type string.

2.Utilise la méthode  du repository Category::class apropriée afin de récupérer l'objet Category correspondant à la chaine de caratére récupérée depuis l’URL.

3.Dans la même méthode, à partir de l’objet Category fraîchement récupéré, appelle la méthode findBy() ou la méthode magique appropriée sur le repository Program::class afin de parcourir toutes les séries liées à la catégorie courante

4.Enfin, ajoute une limite de 3 séries et un tri par id décroissant à la récupération des séries.

5.Crée une nouvelle vue templates/wild/category.html.twig qui affichera toutes les séries récupérés avec leurs id, titres et contenus.


Quête N°9 : Les relations bidirectionnelles avec Doctrine

Pour pouvoir tester cette fonctionnalité et valider cette quête, tu dois créer en BDD plusieurs épisodes (une dizaine) et plusieurs saisons (environ 3) associées aux séries déjà existantes.

Tu peux le faire soit via le terminal, phpMyAdmin, DBeaver, Workbench, PhpStorm ou tout autre outil que tu préférerais.Ce n’est pas sur la qualité du contenu que tu vas être jugé. Mais si tu sèches et que tu tiens à utiliser du contenu cohérent, n’hésite pas à aller sur imdb

Dans la classe WildController, crée une méthode showByProgram() et récupère un programme à partir d’un slug passé dans l’url.

Récupère ensuite toutes les saisons du programme.

Du côté de la vue, fais apparaître chaque saison et rends chacune cliquable, pour mener à la page correspondant à la suite du challenge.

Toujours dans la classe WildController, crée une méthode showBySeason(int $id), qui prend en paramètre l’id de la saison. À partir de cet identifiant issu de l’url, récupère la saison correspondante. À partir de cet objet $season, instance de la classe Season, récupère la série parente à l’aide de getProgram() ainsi que la liste des épisodes associés via getEpisodes().

Le but étant d’avoir sur la vue cet affichage, en respectant bien évidemment le style guide que tu as défini lors de la première quête 

LIEN VIDEO LOOM POUR CHALLENGE : https://www.loom.com/share/9bb1385e7ae441949714bcc457014af5
