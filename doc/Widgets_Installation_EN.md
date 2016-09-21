Widget integration
===================

Javascript
-------------

No matter what kind of widget you wish to display on your website, the following javascript code must be inserted into the page :

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

> - You need to replace "YOUR_LIKIBU_API_KEY" with the API key that we provided you.

#### "Search form" Widget 

![](http://i.likibu.com/doc/widget/widget_search.png)

Insert the following html tag where you want to display the widget : 

    <div data-lkb="1" data-lang="en" data-style="form"><a href="http://www.likibu.com">Likibu</a></div>

> **Notes:**

> - The **data-lkb="1"** attribute is mandatory for the widget to initialize. The widget will not show if this attribute is not present.
> - The **data-style="form"** attribute defines what kind of widget you want to display. "form" means it will display the search form. This attribute is mandatory.
> - The **data-lang="en"** attribute defines the language of the widget. This attribute is optionnal. Default value : en (english). Accepted values : fr, en, es, it, de.
> - The **data-where="Paris"** attribute allows you to set a default search destination. This attribute is optionnal.
> - The **data-width="xxx"** attribute allows you to set the widget's width (in pixels). This attribute is optionnal. Default value : none (widget will take 100% of it's container width)


#### "Search results" Widget - List

![](http://i.likibu.com/doc/widget/widget_offers_list.png)

Insert the following html tag where you want to display the widget : 

    <div data-lkb="1" data-lang="it" data-where="Parigi" data-items="12" data-style="list"><a href="http://www.likibu.com/it">Likibu</a></div>

> **Notes:**

> - The **data-lkb="1"** attribute is mandatory for the widget to initialize. The widget will not show if this attribute is not present.
> - The **data-style="list"** attribute defines what kind of widget you want to display. "list" means it will display a search result widget, as a list. This attribute is mandatory.
> - The **data-lang="it"** attribute defines the language of the widget. This attribute is optionnal. Default value : en (english). Accepted values : fr, en, es, it, de.
> - The **data-where="Parigi"** attribute allows you to set a default search destination. This attribute is optionnal.
> - The **data-items="12"** attribute allows you to set the number of results you want to display. This attribute is optionnal. Default value : 6.
> - The **data-width="xxx"** attribute allows you to set the widget's width (in pixels). This attribute is optionnal. Default value : none (widget will take 100% of it's container width)


#### "Search results" Widget - Mosaic

![](http://i.likibu.com/doc/widget/widget_offers_mosaic.png)

Insert the following html tag where you want to display the widget : 

    <div data-lkb="1" data-lang="fr" data-where="Bruxelles" data-items="12" data-style="mosaic"><a href="http://www.likibu.com/fr">Likibu</a></div>

> **Notes:**

> - The **data-lkb="1"** attribute is mandatory for the widget to initialize. The widget will not show if this attribute is not present.
> - The **data-style="mosaic"** attribute defines what kind of widget you want to display. "mosaic" means it will display a search result widget, as a mosaic. This attribute is mandatory.
> - The **data-lang="fr"** attribute defines the language of the widget. This attribute is optionnal. Default value : en (english). Accepted values : fr, en, es, it, de.
> - The **data-where="Bruxelles"** attribute allows you to set a default search destination. This attribute is optionnal.
> - The **data-items="12"** attribute allows you to set the number of results you want to display. This attribute is optionnal. Default value : 6.
> - The **data-width="xxx"** attribute allows you to set the widget's width (in pixels). This attribute is optionnal. Default value : none (widget will take 100% of it's container width)

#### Callback

It is possible to execute custom javascript code before the user is redirected to likibu.
This allows you, for example, to add tracking codes to the urls.
You need to put your javascript function to the **_lkb.callback** property in order to activate this option.

This functions has 2 arguments : 

> - The javascript event that triggered the conversion (clic on a link, or form submission)
> - The url where the user should have been redirected

Here is an implementation exemple, that redirects to an outgoing link tracking system : 

    <script>
    var _lkb = _lkb || {};
    _lkb.key = "YOUR_LIKIBU_API_KEY";
    _lkb.callback = function(e, url) {
        e.preventDefault();

        window.location.href = 
            'http://www.example.com/outgoing-clic/?url=';
            + 
            encodeURIComponent(url);

        return;
    };
    
    (function (window, document, __lkb) {
        var loader = function () {
        var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
        script.src = "//api.likibu.com/widget/js/widget.1.3.1.js";
        tag.parentNode.insertBefore(script, tag);
        };
        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document, _lkb);
    </script>
