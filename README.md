=== Product Image wrapper for WooCommerce ===

== Wraps the product image in the loop ==

(German version below)

In may situations you are frustrated about the different image heights of your product images. If you not want to 
crop the images you can use this plugin. It puts a wrapper div-element around the images so you can adjust the height by using
css. See the example.

After you activated the plugin, go to Options -> Product wrapper and add or change the specific css rules.

=== Image wrapper für WooCommerce ===

Produktbilder haben oft verschiedene Größen und das lässt sich bei WooCommerce nicht so gut darstellen ohne die Bilder 
automatisch zuzuschneiden. Dieses Plugin platziert um jedes Produktbild ein DIV-Element, sodass du für alle Produkte
dieselbe Höhe setzen kannst. Sieh dir das Beispiel an.

Nach der Aktivierung des Plugins findest du die Einstellungen unter "Einstellungen -> Product wrapper". Hier kannst du 
die Regeln ändern oder neue hinzufügen.


= Example CSS: =

.wciw-product-image-wrapper {
	min-height: 210px;
}

.wciw-product-image-wrapper img {
	width: inherit!important;
	margin: 0 auto !important;
}