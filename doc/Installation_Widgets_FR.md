Intégration des widgets
===================

Javascript
-------------

Quel que soit le type de widget, le code javascript suivant doit être inséré dans la page :

    <script>
    var _lkb = _lkb || {};
    _lkb.key = "YOUR_LIKIBU_API_KEY";
    
    (function (window, document, __lkb) {
        var loader = function () {
        var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
        script.src = "//api.likibu.com/widget/js/widget.1.3.1.js";
        script.async = true;
        tag.parentNode.insertBefore(script, tag);
        };
        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document, _lkb);
    </script>

> **Notes:**

> - Il faut saisir votre clé d'API à la place de "YOUR_LIKIBU_API_KEY".

#### Widget de type "formulaire de recherche"

![](http://i.likibu.com/doc/widget/widget_search.png)

Insérez la balise HTML suivante à l'endroit où vous voulez que le formulaire s'affiche :

    <div data-lkb="1" data-lang="fr" data-where="Paris" data-style="form"><a href="http://www.likibu.com/fr">Likibu</a></div>

> **Notes:**

> - L'attribut data-lkb="1" est indispensable pour que le widget s'initialise correctement. Cet attribut est obligatoire.
> - L'attribut data-style="form" permet de définir que l'on souhaite afficher le moteur de recherche. Cet attribut est obligatoire.
> - L'attribut data-lang="fr" permet de définir que l'on souhaite afficher le widget en langue française. Cet attribut est optionnel. Valeur par défaut : en (anglais). Valeurs possibles : fr, en, es, it, de
> - L'attribut data-where="Paris" permet de définir une destination qui sera pré-remplie sur le formulaire. Cet attribut est optionnel.
> - L'attribut data-width="xxx" permet de définir la largeur (en pixels) du widget. Cet attribut est optionnel. Valeur par défaut : aucune (le widget prendra 100% de la largeur de son container).


#### Widget de type "résultats" - liste

![](http://i.likibu.com/doc/widget/widget_offers_list.png)

Insérez la balise HTML suivante à l'endroit où vous voulez que le formulaire s'affiche :

    <div data-lkb="1" data-lang="it" data-where="Parigi" data-items="12" data-style="list"><a href="http://www.likibu.com/it">Likibu</a></div>

> **Notes:**

> - L'attribut data-lkb="1" est indispensable pour que le widget s'initialise correctement. Cet attribut est obligatoire.
> - L'attribut data-lang="it" permet de définir que l'on souhaite afficher le widget en langue italienne. Cet attribut est optionnel. Valeur par défaut : en (anglais). Valeurs possibles : fr, en, es, it, de
> - L'attribut data-style="list" permet de définir que l'on souhaite afficher des résultats de recherche, en affichage "liste". Cet attribut est obligatoire.
> - L'attribut data-where="Parigi" permet de définir la destination ciblée. Cet attribut est obligatoire.
> - L'attribut data-items="12" permet de définir le nombre de résultats que l'on souhaite afficher. Cet attribut est optionnel. (valeur par défaut = 6)
> - L'attribut data-width="xxx" permet de définir la largeur (en pixels) du widget. Cet attribut est optionnel. Valeur par défaut : aucune (le widget prendra 100% de la largeur de son container).


#### Widget de type "résultats" - mosaïque

![](http://i.likibu.com/doc/widget/widget_offers_mosaic.png)

Insérez la balise HTML suivante à l'endroit où vous voulez que le formulaire s'affiche :

    <div data-lkb="1" data-lang="fr" data-where="Bruxelles" data-items="12" data-style="mosaic"><a href="http://www.likibu.com/fr">Likibu</a></div>

> **Notes:**

> - L'attribut data-lkb="1" est indispensable pour que le widget s'initialise correctement. Cet attribut est obligatoire.
> - L'attribut data-lang="fr" permet de définir que l'on souhaite afficher le widget en langue française. Cet attribut est optionnel. Valeur par défaut : en (anglais). Valeurs possibles : fr, en, es, it, de
> - L'attribut data-style="mosaic" permet de définir que l'on souhaite afficher des résultats de recherche, en affichage "mosaïque". Cet attribut est obligatoire.
> - L'attribut data-where="Bruxelles" permet de définir la destination ciblée. Cet attribut est obligatoire.
> - L'attribut data-items="12" permet de définir le nombre de résultats que l'on souhaite afficher. Cet attribut est optionnel. (valeur par défaut = 6)
> - L'attribut data-width="xxx" permet de définir la largeur (en pixels) du widget. Cet attribut est optionnel. Valeur par défaut : aucune (le widget prendra 100% de la largeur de son container).

#### Callback

Il est possible d'executer du code javascript personnalisé avant la redirection vers likibu. 
Cela donne la possibilité, par exemple, d'ajouter du tracking de votre côté.
Pour activer cette option, il suffit d'assigner votre fonction javascript à pa propriété _lkb.callback

Cette fonction prend 2 arguments : 

> - L'événement javascript ayant provoqué la conversion 
> - L'url sur laquelle l'utilisateur sera redirigé

Voici un exemple d'utilisation, permettant d'ajouter un passage chez eulerian avant de rediriger sur likibu : 

    <script>
    var _lkb = _lkb || {};
    _lkb.key = "YOUR_LIKIBU_API_KEY";
    _lkb.callback = function(e, url) {
        e.preventDefault();

        window.location.href = 
            'http://eulerian.mon-identifiant.com/dyntpclick/identifiant/12345_azerty/12345_azerty/Azerty/section/?ecat=&eurl=';
            + 
            encodeURIComponent(url);

        return;
    };
    
    (function (window, document, __lkb) {
        var loader = function () {
        var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
        script.src = "//api.likibu.com/widget/js/widget.1.3.1.js";
        script.async = true;
        tag.parentNode.insertBefore(script, tag);
        };
        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document, _lkb);
    </script>
