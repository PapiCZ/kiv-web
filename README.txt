* app - Hlavní logika aplikace
    * Http - Jedná se o soubory, které se nějak přímo podílí na zpracování HTTP požadavku.
        * Controllers - Složka s controllery aplikace
    * Models - Modelová vrstva aplikace
    * Validators - Validátory pro jednotlivé formuláře aplikace
* bootstrap - Skripty, které se starají o inicializační nastartování aplikace
* core - Jádro aplikace. Velmi zjednodušeně řečeno se zde nachází {\it micro-framework}, který umožňuje celou aplikaci vyvíjet pohodlněji.
* public - Veřejná část webu. Zde se také nachází {\tt index.php} jako jediný PHP soubor. Díky tomu není možné spouštět z veřejné části webu jiné PHP soubory než právě zmíněný {\tt index.php}, což přináší větší bezpečnost celé aplikace.
    * dist - Zkompilované CSS a JavaScript soubory
* resources - Složka se skripty, styly, šablonami a daty pro validátor formulářů
    * scripts - Zdrojové JavaScript soubory před kompilací
    * styles - SCSS soubory
    * templates - Šablony pro šablonovací systém Twig
    * validation - Data pro validační systém. Chybové hlášky a překlady názvů jednotlivých polí.
* routes - Jednotlivé cesty v aplikaci. Zde se nachází mapování URI adres na konkrétní metodu v konkrétním controlleru.
* storage - Úložiště aplikace
* .babelrc - Informace pro převedení JavaScriptu do srozumitelné podoby pro prohlížeč.
* .env - Proměnné prostředí. Slouží ke konfiguraci aplikace.
* .env.example - {\it Šablona} pro {\tt .env}. Pokud soubor {\tt .env} neexistuje, tak je nutné ho vytvořit jako kopii tohoto souboru. Tento soubor aplikace ignoruje.
* composer.json - Konfigurace aplikace pro balíčkovací systém composer.
* gulpfile.js - Postupy kompilace pro jednotlivé části aplikace pro nástroj gulp.
* package.json - Konfigurace pro balíčkovací systém npm.
* README.md - Návod pro instalaci aplikace
