Technische implementatie Thnk elearning website


Datum: 20 December 2011 - 3 Januari 2012
Door: Bas Brands
Email: bas.brands@brightalley.nl


LOG:
23-1-2012: Share document met Job, Joke, Onno
23-1-2012: [Onno, 11:30] Nummering sectie 4 gecorrigeerd. Paragrafen 4.2.1 en 4.2.2 toegevoegd over filteren op gebruikers en contact-restricties.
24-1-2012: [Onno] Paragraaf 4.2.1 aangepast (filters vs. ordeningen)
30-1-2012: [Onno] Paragraaf 4.2.2 (Contact-restricties) aangepast
31-1-2012: [Onno] Paragraaf 4.2.2 (Contact-restricties) kleine uitbreiding
31-1-2012: [William] Par 1.2.3 (Homepage stream): details toegevoegd nav skype-gesprek met Bas
6-2-2012: [Bas] Paragraaf 5.3 toegevoegd over groepsstructuren en courses. (Challenges/Collaborators)
6-2-2012: [Bas] Paragraaf 5.1.5 informatie over file repositories toegevoegd.
8-2-2012: [Onno] Paragraaf 4.2.3 core aanpassing messages toegevoegd.
9-2-2012: [William] Paragraaf 1.2.1 Search specificaties aangepast
9-2-2012: [Onno]: 4.2 Link naar connector verschijnt alleen als connector ook op homepage staat.
1-3-2012: [Onno] 5.1.4 Activiteiten lijst - kopje “technische implementatie” toegevoegd
13-3-2012: [Onno] Implementatie details Search toegevoegd onder 1.2.1.
15-3-2012: [Bas] Hacks voor Blog en Profile 2.3 HACKS
15-3-2012: [BAS] Added HACKS section at end of file


Bronnen voor de Technische implementatie:
* thnk lnk concept description 20111103-2.pptx
* thnk lnk mockups door Leo en Bas
* input uit samenvatting overleg Joke & Job bij Think eind December.
* Input uit overleg bij THNK 10 januari 2012 met Mark van Ooij


Context:
Dit document beschrijft de globale inspanningen vereist voor het realiseren van het voorstel dat is opgeleverd voor het prototyping overleg tussen BrightAlley en Think. In deze fase van het project zijn nog niet alle screens en functionaliteiten geheel uitgewerkt. Het is wel een leidraad voor het opstellen van een definitief functioneel en technisch ontwerp.


= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
1. HOME
= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =


1.1 THNKLNK homepage, functioneel


1.1.1 Inleiding
Op de homepage kan een gebruiker inloggen en algemene informatie vinden over de thnklnk omgeving. De belangrijkste functie van de homepage is een portaal functionaliteit die de basis vormt voor de verdere navigatie op de thnklnk omgeving. Daarnaast heeft het portaal als functie het weergeven van activiteiten op de thnklnk omgeving. De Stream levert daar de belangrijkste input voor. Door het inzetten van een Stream (denk aan een Twitter stream) wordt de gebruiker geprikkeld deel te nemen aan thnklnk.


1.2 Functionele Indeling Homepage
De Moodle homepage bestaat uit 3 kolommen. Deze kolommen bevatten:


1.2.1 Kolom 1: Homepage menu navigatie, search en login
Positionering en content
De meest linkse kolom op de homepage bevat informatie die op alle pagina's van thnklnk beschikbaar blijft. Deze balk wordt links van het content panel en de stream getoond bij een browser scherm groter dan 1236 px. Is het scherm kleiner, dan wordt de balk boven de content en stream kolom getoond.
In deze kolom vind de gebruiker profiel informatie, een login formulier of een ingelogd als info box, een zoekfunctie en een geanimeerd menu.


Navigatie
Er wordt een navigatie menu ontwikkeld op basis van het THNKBATS design, de stervormige graphic. Dit menu toont bovenin het actieve menu item. Hover je over de andere punten van de ster dan worden de overige elementen getoond. Deze overige elementen zijn de beschikbare apps voor de gebruiker. Het navigatie menu wordt alleen getoond wanneer de gebruiker ingelogged en geauthenticeerd is.


Search
- De zoekfunctie toont bij het intypen van een zoekwoord resultaten in het content venster. [Met een druk op de knop i.p.v. ajax]
- Er kan worden gezocht op alles in Moodle, mits de gebruiker daar voor geautoriseerd is.
- Gebruikers kunnen ook tags toevoegen aan content [todo: afspreken welke mods - calendar, forum posts -- of comments block gebruiken?] of het eigen profiel.
- Zoekresultaten op tags worden apart getoond in het content venster, hierna is het mogelijk de resultaten verder te filteren (advanced search).
- Zoekresultaten worden per app [= wiki (ouwiki), collaborator (forums, files, events), connector (personen, profielen)] verzameld en met ‘pagination’ getoond als er meer dan x resultaten zijn [pagination vindt plaats op speciaal daarvoor bestemde pagina, waarop alleen results van een specifieke app staan].
- Zoekresulaten van alle apps [wiki (ouwiki), collaborator (forums, files, events), connector (personen, profielen)] worden getoond bij de initiele search. Daarna kan er gefilterd worden op een specifieke app. 
- Zoekresultaten worden beperkt tot de groep en de course waar de user in zit of toegang tot heeft. Als een app-bericht geen groupid heeft, wordt alleen naar de courseid gekeken.
- Per teruggegeven resultaat wordt niet meer gecheckt op capabilities. Hiervoor is gekozen omdat het checken van capabilities serverintensief is en dit voor alle zoekresultaten zou moeten gebeuren om de pagination op te bouwen. Restrictie op courseid en groupid is veel minder serverintensief en verzorgt tot op zekere hoogte dezelfde taak. 


Search Implementatie


De search is een Block in de map blocks/tl_search
Search maakt gebruik van de OUSearch in local/ousearch. Hiervan wordt voornamelijk de file require_once($CFG->dirroot.'/local/ousearch/searchlib.php');
gebruikt.


In local/ousearch vind je de file ousmodules.php. Hierin staan classes die worden gebruikt voor het indexeren van verschillende document-types, zoals een forumpost, forumfile, activity, course resource. De wiki is hierop een uitzondering. Deze is door de Open University zelf gebouwd en heeft al een koppeling met de OUSearch.
Onderaan ousmodules.php vind je een aantal functies die verplicht aanwezig moeten zijn voor iedere module, omdat de search results renderer daar naar op zoek gaat.


Nadeel van de OUSearch is dat alle posts apart moeten worden aangeboden, zodat ze in de zoekindex terecht komen. Als voorbeeld het forum:
in mod/forum/lib.php staat bovenaan de volgende regel:


 include_once(dirname(__FILE__).'/../../local/ousearch/searchlib.php');


Voor het updaten van een forumpost in de zoekmachine, kun je dan na het insert-statement de volgende code opnemen:


        if (defined('OUSEARCH_FOUND')) {
           ousforum::update($post, $cm);
        }


Zie de class ousforum voor de implementatie.

Het updaten van files is wat lastiger:

        if (defined('OUSEARCH_FOUND')) {
           $fs = get_file_storage();
           $files = $fs->get_area_files($context->id, 'mod_forum', 'attachment', $post->id);
           foreach ($files as $f) {
               // $f is an instance of stored_file
               $filename = $f->get_filename();
               $search_post = (object)array(
                   'id' => $f->get_id(),
                   'userid' => $f->get_userid(),
                   'filename' => $f->get_filename(),
                   'subject' => $post->subject
               );
               ousforum_file::update($search_post, $cm);
           }
        }


Let op dat files niet inhoudelijk worden geindexeerd, maar alleen de filename en de context (forum post title).

Login
Gebruiker kunnen inloggen met een LinkedIn profiel als dit beschikbaar is. Zo niet dan kunnen gebruikers zichzelf aanmelden als nieuwe gebruiker. Voor nieuwe gebruikers moet worden vastgesteld welke gebruikers informatie minimaal noodzakelijk is voor deelname aan THNKLNK. Iedereen kan een account aanmaken of inloggen met zijn linkedin profiel. Het is daarom noodzakelijk om gebruikers pas toegang te geven op de apps als zij ook geauthenticeerd zijn. Er zal een mechanisme moeten worden ingezet om deze authenticatie te kunnen uitvoeren. Bijvoorbeeld door de gebruiker na inloggen een authenticatie code (of voucher) te laten invoeren.
Meld een gebruiker zichzelf aan dan wordt er een inschrijf formulier getoond in het content gedeelte. In dit inschrijf formulier wordt de gebruiker gevraagd om minimaal de volgende veld in te voeren
- verplichte gebruikers velden
* gebruikersnaam
* voornaam
* achternaam
* wachtwoord
* email adres
- optioneel
* upload foto (indien geen foto dan THNKBATS als profielfoto)
* bedrijfsgegeven
* omschrijf jezelf
* verberg mijn profiel voor anderen

- kolom 1: (gast)
* THNK logo
* login formulier
* Geanimeerd menu
* Infotext

- kolom 1: (ingelogd)
* THNK logo
* inlog informatie
* Globale search functie
* Geanimeerd menu
* Infotext

1.2.2 Kolom 2: Homepage content panel
Het content panel bevat algemene informatie over de verschillende “apps” die beschikbaar zijn op THNKLNK. Er is een verschil tussen de content die een gast te zien krijgt en de content die een geauthenticeerde gebruiker met de juiste autorisatie te zien krijgt.
Wanneer de hoeveelheid content meer dan 500px hoog is (ruwe schatting) dan kan het volgende onderdeel in de content via een uitschuif knop getoond worden, zoals dit ook op de thnk.org pagina is gedaan.

De horizontale balken worden geanimeerd via Javascript.

- kolom 2 (gast):
* Team Collaborator: Link naar de actieve challenge
* Wiki: Directe links naar de beschikbare wiki's
* Gallery: Info uit gesloten (oude) challenges

- kolom 2 (ingelogd):
* Codex: login informatie of je LinkedIn profiel
* Connector: Link naar de connector space.
* Toolshed: Directe links naar de beschikbare Toolsheds (ook wiki's)

* Team Collaborator: Link naar de actieve challenge
* Wiki: Directe links naar de beschikbare wiki's
* Gallery: Info uit gesloten challenges

1.2.3 Homepage Stream
De werking van de Stream
De stream toont een aantal verschillende typen activiteiten. Dit kunnen activiteiten zijn als stream berichten (een soort Tweets), acties uitgevoerd binnen een team collaborator (=Moodle cursus) zoals file uploads, new event, new activity, new file, new forum post. De stream bevat ook je persoonlijke berichten en mentions.

De stream is opgedeeld in de THNKLNK stream (*) en de persoonlijk stream (@). In de THNLNK stream (*) zijn alle algemene activiteiten en stream berichten van gebruikers te zien. De algemene activiteiten worden gevoed door het platform, zodra er dus in een collaboration space een bestand wordt toegevoegd dan wordt hier ook een stream update aangemaakt.
In de persoonlijk stream (@) zijn alle direct messages en eigen geplaatste stream berichten te zien.
De Stream interface toont ook de mogelijkheid om direct een nieuw streambericht te plaatsen. 
Indien er nieuwe berichten zijn, dan worden deze direct in de stream getoond (ajax update). 
Suggestie: Bij persoonlijke berichten krijgt de gebruiker de mogelijkheid om door te klikken naar het message systeem van Moodle (lees verder). 
Suggestie: om verschillende berichttypen van elkaar te onderscheiden, krijgt ieder bericht in de stream een eigen icoontje. Voorlopig is dit nu een vierkant blokje voor de titel van het bericht in een bepaalde kleur.
Notificaties voor updates binnen de collaboration space (=cursus) wordt via event triggers geregeld. Als eerste aangewezen modules die hiervoor in aanmerking komen zijn het forum en kalender events. Er dient rekening gehouden te worden met de capabilities van de user of (forum)berichten wel of niet in de stream worden weergegeven.
Streamberichten worden intern per user gecachet. Default cache time staat nu op 5 minuten.

Configuratie van de stream:
Het is nog de vraag of alle informatie van gebruikers en het platform geschikt is voor alle bezoeker van THNKLNK. Welke informatie ingelogde gebruikers te zien krijgen is te configureren via de Stream configuratie pagina.


- kolom 3 (gast)
* Stream is leeg


- kolom 3 (ingelogd + geauthoriseerd)
* Stream met activiteiten op THNLNK op chronologische volgorde, mits geauthoriseerd (capability check).
* persoonlijke Stream met berichten gericht op ingelogd persoon + eigen Stream activiteiten.
* Content uit de codex komen in de Stream, wel nieuwe blogposts, niet de tekst uit de personal development omgeving.


1.2.4 Homepage sitemap
De sitemape is een statische lijst van links. Deze kunnen worden beheerd door een admin gebruiker via een formulier op de homepage.


1.3 Technische implementatie homepage


1.3.1 Standaard vs Maatwerk
Een gedeelte van de functionaliteiten worden standaard bij het Moodle LMS geleverd. Een gedeelte zal gebouwd moeten worden. De onderdelen die ontwikkeld moeten worden:


* * * * * * * * * * * * * * * * * * * *   
* Stream                                                     3 weken
* Search                                                     2 weken
* Geanimeerd menu                             5 dagen
* LinkedIn login                                    8 dagen
* Authenticatie systeem             5 dagen
* * * * * * * * * * * * * * * * * * * *  


Voor Thnk zal de grafisch vormgeving worden omgezet naar een Moodle template. Dit template zal het grootste deel van de grafisch elementen zoals loginboxes, sliders, geanimeerde menu's etc zoveel mogelijk met CSS 3 vormgeven. Er wordt daarbij rekening gehouden met de wat oudere browser die enkel cSS 2 elementen ondersteunen. Voor het menu wordt HTML 5 gebruikt.


= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
2. CODEX
= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =


Voor de invulling van de codex pagina worden “Elly en Robert” in de 3e week van januari benaderd. Dit is inmiddels gedaan en er is afgesproken dat de Codex pagina een openbare pagina wordt die voor mensen die: 
- de link naar de codex hebben
- mensen die zijn ingelogd op THNKLink en bijvoorbeeld in de connector op een profielfoto klikken.


2.1 THNKLNK Codex page
De codex is een persoonlijke omgeving voor deelnemers. Hierin is het LinkedIn profiel van de gebruiker te zien, een mission statement (of Quest), een blog. De codex pagina heeft net als de homepage het menu aan de linkerzijde of bovenaan afhankelijk van de grootte van het browser scherm.


2.1.1 Portfolio / Blog
Het Portfolio is een lijst van artikelen die de gebruiker in de loop van de tijd opbouwt. Dit portfolio kan binnen THNKLNK worden beheerd of via een RSS feed van een blog die de gebruiker al heeft. Bijvoorbeeld Blogger. Moodle voorziet al in de forumulieren structuren voor het aanmaken van een blog items / importeren van blogs.


2.1.2 Profiel
Wanneer gebruikers zijn ingelogd via een  LinkedIn profiel dan wordt hier je profiel getoond met een knop naar het gebruikersprofiel op LinkedIn zelf. Wanneer ze een THNKLink account hebben aangemaakt zal dit account te zien zijn. Hierin wordt de volgende informatie getoond: 
- Voornaam en Achternaam
- Profielfoto
- Functie


2.1.3 Leadership Philosophy
In de leadership philosophy geeft een gebruiker weer wat zijn doelen zijn. Dit heeft een eigen admin interface waarmee de inhoud beheerd kan worden. De Leadership philosophy is bedoeld als ‘visie van de participant’  en kan dus wijzigen. 


2.2 Technische implementatie Codex
De codex heeft het karakter van een persoonlijk Dashboard zoals deze beschikbaar is in Moodle’s standaard MyMoodle page. Bij gebruik van de MyMoodle page is het mogelijk een flexibel persoonlijk Dashboard systeem te bouwen. Hiervoor moeten de volgende onderdelen ontwikkeld worden:


2.3 Hacks
De Moodle blog pagina heeft geen optie voor custom blokken, daarom zijn de $PAGE variabelen aangepast:


vanaf regel 245:
//HACKED BY BAS BRANDS, enable using blocks
$header = $SITE->shortname  . ': ' . get_string('blog','blog');
$params = array();
$context = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_context($context);
$PAGE->set_url('/blog/index.php', $params);
$PAGE->set_pagelayout('standard');
$PAGE->set_pagetype('blog');
$PAGE->blocks->add_region('content');
$PAGE->set_title($header);
$PAGE->set_heading($header);


Voorkom dat guest users het guest blog te zien krijgen: 
vanaf regel 85


//HACKED BY BAS BRANDS, no guest or global blog.
/*
 if (!$userid && has_capability('moodle/blog:view', $sitecontext) && $CFG->bloglevel > BLOG_USER_LEVEL) {
 if ($entryid) {
 if (!$entryobject = $DB->get_record('post', array('id'=>$entryid))) {
 print_error('nosuchentry', 'blog');
 }
 $userid = $entryobject->userid;
 }
 } else if (!$userid) {
 $userid = $USER->id;
 }*/
if (!$userid ) {
        $userid = $USER->id;
}


if ($userid  == 0 || $userid == 1) {
        redirect($CFG->wwwroot . '/login/index.php');
}
//END OF HACK


en in lib.php va 689
        //hacked by bas brands
        if (!$userid ||$userid == 0) {
            $userid = $USER->id;
        }


* * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Dashboard pagina met een embedded Moodle blog   3 dagen
* Tonen van het LinkedIn profiel                                   1 dag
* Ontwikkelen van een Mission Statement block         5 dagen
* * * * * * * * * * * * * * * * * * * * * * * * * * * * *


= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
3. WIKI
= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =


3.1 THNKLNK Wiki
De Wiki is een plaats waar kennis gedeeld wordt over de basis vaardigheden “Transformations”, “The Creation process”, “Leadership”. De Wiki is ook de plaats waar een lijst van tools voor de vier fases van het THNKLNK ontwikkel proces: “Sensing”,”Visioning”,”Prototyping” en “Scaling”. De inhoud van de Wiki zal worden aangevuld door deelnemers en de beheerders van THNKLNK.


3.1.1 Moodle Wiki
Moodle biedt een eenvoudige Wiki, hiermee kunnen gebruikers met de juiste rechten teksten met opmaak en afbeeldingen toevoegen. De Moodle maakt onder ander gebruik van de volgende rechten:
Wiki pagina toevoegen / bewerken,
Commentaar toevoegen / bewerken,
Wiki settings aanpassen,
Commentaar bekijken,
Wiki pagina bekijken.


De Wiki Tool maakt gebruik van Wiki opmaak, bijvoorbeeld door het gebruik van “[LINK]” notatie voor het verwijzen naar een andere Wiki pagina. Een Wiki heeft doorgaans een moderator nodig die de inhoud controleert en zorgt voor een consistente structuur.


Voor de Technisch implementatie zal de meeste tijd in het opzetten van een duidelijke structuur in combinatie met een goede vormgeving het succes van de Wiki bepalen.


3.2 Technische implementatie WIKI
Voor de Wiki hoeft alleen een goede vormgeving te worden ontwikkeld. De tijd voor technische implementatie zal vooral bestaan uit het goed inrichten van de Wiki:


* * * * * * * * * * * * * * * *
* Inrichting Wiki           2 dagen
* * * * * * * * * * * * * * * *


= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
4. CONNECTOR
= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =


4.1 THNKLNK Connector
In de connector kunnen gebruikers op een aantrekkelijke manier communiceren met elkaar en met experts. In de connector zijn daarvoor de volgende activiteiten beschikbaar.
* Creative Sparks
* Surveys (kan ook een link naar surveymonkey zijn)
* Expert Network


De Connector kan gebruik maken van de messaging interface die al in Moodle is ingebouwd. Deze messaging interface werkt goed maar kan in een standaard Moodle omgeving wel wat aantrekkelijker worden weergegeven. Surveys kunnen ook in Moodle worden beschikbaar gesteld door middel van de Moodle questionnaire plugin.


4.1.1 Creative Sparks
Bij het creation process worden 4 fasen doorlopen: sensing, visioning, prototyping, scaling.
Bij de overgang van sensing naar visioning kan blijken dat er of te weining goede oplossingen zijn gevonden of dat de sensing fase niet goed genoeg is uitgevoerd. Om ideeen te testen kan een participant een ‘creative spark’  uitzetten om ideeen te genereren. 


Creative Sparks zijn bedacht om gebruikers die vastlopen in een Challenge de kans te geven vragen te stellen aan experts en andere gebruikers. In de mockup is hiervoor een mobile chat app nagebootst waarin de aanwezige gebruikers een duidelijke persoonlijke foto hebben in de vorm van een Grid. In dit grid is in één oogopslag duidelijk welke gebruikers je een berichtje hebben gestuurd. Hover je over een gebruikers foto dan wordt er wat profiel informatie getoond.
De creative sparks moeten op een logische volgorde worden weergegeven. Eerst de personen waarmee je al berichten hebt uitgewisseld, dan de mensen die recent zijn ingelogged enz.
Het aantal gebruikers kan flink gaan groeien, bij de creative sparks moet het mogelijk zijn om te zoeken. Dat kan zijn op tags die gebruikers aan het profiel hebben toegevoegd of de cohort waarin de gebruiker zit.
Er is een verschil tussen gebruiker met een ‘executive’* status en deelnemers aan de open course. Het is niet de bedoeling dat iedere gebruiker zomaar contact kan opnemen met de gebruikers die een ‘executive’ status hebben.
Bovenin de creative sparks is een link beschikbaar waarmee je je status kunt aanpassen
* niemand mag contact met mij opnemen
* alleen executive users mogen dit
* iedereen mag dit.


* executive = deelnemers van het executive programma.


4.1.2. Surveys
De moodle questionnaire plugin kan verschillende type formuliervragen verwerken. Radiobutton, textfield textarea enz. De resultaten van de questionnaire zijn standaard enkel voor een gebruiker met een teacher / admin functie te bekijken. Er kan worden ingesteld dat ook normale gebruikers de ingevulde antwoorden kunnen bekijken. Het is voor een gebruiker niet eenvoudig zelf een questionnaire samen te stellen.


4.1.3 Expert Network
Naast een database van gebruikers in de Moodle omgeving zijn er ook externe experts die benaderd kunnen worden. Hiervoor zijn externe databases beschikbaar. Deze databases staan los van het THNKLNK systeem en vereisen geen Moodle maatwerk.


4.1.4 Voorwaarden voor de communicatie tussen gebruikers.
Voordat gebruikers beginnen met het gebruik van een communicatie systeem moet worden nagedacht zaken als privacy en gebruikers gemak. Welke communicatie is privé? Hoe weet de gebruiker of iets publiek is of privé? Hoe weet een gebruiker dat er berichten open staan (notificatie methoden). Hoe lang blijven berichten bewaard? Kan een gebruiker een bericht zelf verwijderen? Kan een gebruiker zijn eigen profiel gemakkelijk aanpassen / verwijderen?


4.2 Technische implementatie Connector.
De connector bouwt verder op het Moodle messaging systeem. Hierin is nog geen gebruikers overzicht beschikbaar. Het geheel kan worden opgebouwd met een Moodle plugin. Door een chat app als voorbeeld te nemen wordt het later ook gemakkelijker om de Connector als app aan te bieden. Vanuit de connector “portretten” kom je in het feitelijke messaging systeem van Moodle. Als de connector op de home page staat, verschijnt er ook een link terug vanuit het messaging systeem naar de portretten.

Om de vereiste functionaliteit flexibel te kunnen aanbieden, bouwen we twee generieke onderdelen. We realiseren de specifiek genoemde beperkingen voor e.g. executives users met defaults en configuratie.


4.2.1 Filteren op gebruikers
Je kunt gebruikers in het foto-overzicht uiteindelijk filteren op:
1. groups / cohorts / courses
2. tags in profiel
Gebruikers worden automatisch altijd geordend op: recent contact, recent ingelogd. Er is verder een limiet van 100 weergegeven gebruikers.


4.2.2 Contact-restricties
Je kunt zelf bepalen wie contact met jou mag opnemen.
"I can only be contacted by:
1. members of my group
2. users with this role: {student, teacher, … , x}
3. people listed as contacts (standaard Moodle, bestaat al vinkje voor, maar naam is gewijzigd)”


De contact-restricties worden ingesteld in Settings > My profile settings > Messaging.


Als de executive users uiteindelijk als gebruikers met een bepaalde rol worden gerealiseerd, kunnen we de default setting voor deze gebruikers op "I can only be contacted by users with the same role as me" zetten.


N.B.: “geen contact mogen opnemen” is niet hetzelfde als “onzichtbaar zijn” - het is onrealistisch om iedereen naar wens “onzichtbaar” te maken voor andere rollen o.i.d.. Dat zou alleen kunnen in het portretten-overzicht van de Connector zelf (hoewel we dat daar ook niet doen), maar zeker niet in heel Moodle zelf.


“Geen contact opnemen” wordt gecontroleerd in de Moodle message module zelf. Dat betekent dat je een waarschuwing krijgt als je probeert contact op te nemen (“connect”) met iemand die dat niet toestaat.


4.2.3 Core aanpassingen

Bestand: message/index.php
Regel 231: link naar connector toegevoegd.


* * * * * * * * * * * * * * * * * * * *
* Bouw communicatie plugin            2 weken
* * * * * * * * * * * * * * * * * * * *


= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
5. COLLABORATOR
= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =


5.1 THNKLNK Collaborator
De Collaborator is de plaats waar een Challenge in gefaciliteerd wordt. De Collaborator bevat daarvoor:
* Kalender
* Activiteiten lijst
* Werkbestanden
* Meeting place, bijvoorbeeld Webex.


De Collaborator kan worden vertaald naar de Moodle cursus pagina. Dit is plaats waar in Moodle de gebruikers het meest actief zijn. De collaborator maakt ook hier gebruik van het 3 kolommen menu. De linker kolom bevat het standaard menu, de middelste de content en de rechter de kalender en de deelnemers van de Challenge.
Gebruikers in de collaborator zijn ingedeeld in gescheiden groepen.


5.1.1 Collaborator Menu
In de mockup zijn deze elementen beschikbaar via een menu bovenin de Collaborator waarin 5 elementen beschikbaar zijn:
* Challenge outline
* Message Board
* Activiteiten lijst
* Werkbestanden
* Kalender
Het Collaborator menu is een actief menu waarvan het geactiveerde item groot wordt weergegeven. In de mockup is te zien dat het Message board 2 nieuwe berichten heeft.


Voor de Collaborator wordt een Moodle course format ontwikkeld vergelijkbaar met het “tabbed” course format dat kan worden gedownload van moodle.org. Een Moodle course bestaat uit een instelbaar aantal topics of sections. In een Section kunnen meerdere teksten of activiteiten worden toegevoegd. Het kan worden vergeleken met een HTML pagina met daarin tekst en links naar forums, opdrachten en toetsen. Het course format bepaald hoe de verschillende Sections worden weergegeven, als lange pagina of onderverdeeld in bijvoorbeeld tabs.


5.1.2 Challenge outline
De Challenge outline is de beschrijving van de Challenge. Hiervoor wordt Section 0 van de Moodle course page gebruikt waarin de Challenge tekst als een Moodle “Label resource” wordt toegevoegd.


5.1.3 Message board
Het Message board is een Moodle forum waarin gebruikers topics kunnen posten en op topics kunnen reageren. 
Voor het forum wordt gebruik gemaakt van het standaard Moodle forum. De course layout heeft een eigen functie waarmee de forumtopics met hun content worden getoond. Via de knop discuss this topic kunnen de reacties op het topic bekeken worden en kunnen gebruikers zelf een reactie plaatsen. 


Er worden enkel forum topics getoond van gebruikers in jouw grouping. 


5.1.4 Activiteiten lijst
De Activitity list is opgebouwd uit de cursusactiviteiten die aan Section 2 van de cursus zijn toegevoegd. 


De activiteiten lijst functioneert als een Moodle module, maar moet direct als activiteit in de cursus zichtbaar zijn. Voorbeeld hiervoor is de forum functionaliteit op http://thnklink.bash01.nl/course/view.php?id=5. Hier worden forum topics direct in een course section getoond zonder dat eerst op een link naar het forum wordt geklikt. Hiervoor is een aparte functie geschreven in het topcol course format dat beschikbaar is in Github. 
(tech) Zie:
/course/format/topcoll/format.php
line 240: if ($section == 3) … etc .. etc..


Voor de activiteiten lijst moet er dus zowel een aparte moodle module worden gebouwd als een functie in het course format die deze lijst binnen section 2 laat zien.


Rechten voor activiteiten beheer
Voor de activiteiten lijst zijn de volgende capabilities toegevoegd:
edit_own_activity (create, read, update, delete van eigen activiteiten*)
check_activities (afchecken van de aan jou toegewezen activiteiten)
view_activities (read all activities for your group)
edit_all_group_activities (create, read, update, delete voor activiteiten binnen jouw group)
edit_all_activities (edit all activities in the course)
 * eigen activiteiten zijn activiteiten die jij zelf hebt toegevoegd.


Zichtbare activiteiten
Student zien de activiteiten voor 1 groep, is de studenten lid van meerdere groupen dan is in de sessie opgeslagen in welke groep op dit moment gewerkt wordt. Alleen de activiteiten voor deze groep worden in het course format getoond. 

Gebruiker interactie
De activiteiten lijst gedraagt zich als de tinytodolist http://www.mytinytodo.net/demo/

Activiteiten worden door middel van een formulier direct in de activiteiten lijst gezet zonder herladen, of via de add button toegevoegd, dit toont het edit activity form met alle opties.

Activiteiten kunnen afgevinkt worden, en hebben een edit knop, de edit knop heeft geen “Move to” optie.

Activiteit data:
-id
-userid
-courseid
-groupingid
-groupid
-taakomschrijving
-aanmaakdatum
-richtdatum
-toegewezen aan: (een group member)
-prioriteit
-notitie
-status (checked)


Technische implementatie
De feitelijke implementatie is de Moodle module mod todotwo. Deze module heeft de local plugin Soda nodig (master branch).


5.1.5 Werkbestanden
De Sources zijn de bestanden die aan de Challenge zijn gekoppeld. De wens is om een soort file/folder structuur te kunnen gebruiken. File voor file uploaden is niet gewenst er moet een slimme structuur mogelijk gemaakt worden. Denk hierbij aan een repository achtige functionaliteit. 
Als voorbeeld voor de werkbestanden is het dropbox systeem gegeven. Het is nog niet duidelijk hoe dit dan in Moodle moet worden uitgevoerd.
Wat wel duidelijk is:
* bestanden in de repository zijn alleen beschikbaar voor 1 Groep 
* gebruikers kunnen elkaars bestanden openen ,aanpassen en verwijderen binnen de groep
* per bestand moet het mogelijk zijn om (ongestructureerde) tags toe te voegen waar later in de global search op gezocht kan worden.
* bestanden die in de repository zijn gezet zijn het eigendom van een groep, niet van een persoon. 


 Er dienen nog een aantal vragen te worden beantwoord door Thnk om hier een functioneel ontwerp voor op te zetten:
* wat is de maximaal toegestane grootte van een bestand
* wat is het maximaal aantal bestanden?
* hoe lang worden bestanden bewaard?
* welk type bestanden is toegestaan?


Onlangs is het drag & drop block vrij gegeven op Moodle.org. Dit kan een mooie basis zijn voor een gemakkelijk te gebruiken file repository.


5.1.6 Kalender
Rechtsbovenin de Collaborator wordt de kalender getoond. Deze kalender toont events toegevoegd via de event manager. Gebruikers kunnen een event toevoegen (deze functionaliteit is nog niet in de mockup verwerkt). Bij het klikken op de kalender maand word de kalender in het content gedeelte van de cursus getoond.
De faculty wil graag in 1 keer een event voor een challenge kunnen neerzetten. Een event vanuit de faculty moet voor alle groups zichtbaar zijn. Een event binnen een challenge group niet


5.1.7 Challenge Group
De Collaborator (de Moodle cursus) maakt gebruik van gescheiden groepen deelnemers. Een deelnemer van een Challenge Group ziet daardoor alleen gebruikers die lid zijn van dezelfde groep als hij/zijzelf. In de rechterkolom worden de fotos van de deelnemers aan de Challenge Group getoond. Bij een hover over wordt de naam van de gebruiker getoond. Bij een klik op de foto wordt de Codex pagina voor deze gebruiker getoond.


5.2 Technische implementatie Collaborator
De Collaborator maakt veel gebruik van standaard Moodle functionaliteiten. Wat de Collaborator vooral anders dan standaard maakt is het Course format. Er zal hier veel aandacht nodig zijn voor een duidelijke navigatie structuur voor een zo eenvoudig mogelijk gebruik.


Er bestaat nog geen functionaliteit in Moodle die de fotos van alle groepsleden toont. Deze functionaliteit kan worden toegevoegd door het ontwikkelen van een nieuwe Moodle block.


5.3 NIEUWE WENSEN:
Collaborator = course groepen. 
Er moet een werkwijze worden ontwikkeld waardoor gebruikers die ingelogged zijn via Linkedin de authorizatie krijgen om een nieuwe Collaborator aan te maken. Dit kan doordat bijvoorbeeld een admin een groep binnen een course aanmaakt. Deze groep krijgt dan bijvoorbeeld een naam als “T-Mobile”. De gebruiker die de groep heeft aangemaakt krijgt vervolgens de rechten om andere gebruikers uit te nodigen om deel te nemen aan de groep. 
Deze gebruiker is vanaf dat moment een Team leader.


Challenges = moodle cursus
In de omgeving wordt gebruik gemaakt van Challenges; een thema waar gebruikers samen aan gaan werken. Een Challenge heeft een onderwerp en een omschrijving. Dit onderwerp is zichtbaar en gelijk voor alle Collaborators. 
Op de frontpage van Moodle zijn normaal alle cursussen zichtbaar. Thnk gebruikt echter maar 1 Challenge per keer. Gebruikers die deelnemen aan een challenge moeten bij het klikken op de Challenge naam een menu gepresenteerd krijgen waarin ze de volgende keuzes kunnen maken:
- Toon collaborators waar de gebruiker al lid van is. 
- Selecteerd een Collaborator en verzoek toegang
- Stuur een verzoek voor het aanmaken van een nieuwe Collaborator
- Zoek een Collaborator


Door gebruik te maken van het groep moet thnk rekening houden met het volgende:
- Collaborators hebben altijd dezelfde standaard opmaak
- Er word gebruik gemaakt van group keys die de teamleader moet verspreiden
- Er moet een oplossing worden gevonden in het geval een gebruiker lid is van meerdere groepen. Hoe wordt dit in de cursus getoond?


GESCHATTE EXTRA ONTWIKKELTIJD:


- inrichting groeps structuren groeps keys: 3 dagen
- tussenscherm voor groeps selectie: 3 dagen
- groeps request: 1 dag


* * * * * * * * * * * * * * * * * * * * * * * *
* ontwikkeling course template              3 dagen
* ontwikkeling Collaborator menu            1 dag
* Challenge Group (block)                   1 dag
* * * * * * * * * * * * * * * * * * * * * * * *

6. Hacks:

/blog/index.php
Regel 232 tm 241: $Page variablen toegevoegd voor het toevoegen van blocks
Regel 85  tm 99 : zorg dat er altijd een userid is
/blog/lib.php
Regel 687 tm 690: geen blog voor guest

/user/edit.php
Regel 265: Redirect gebruiker terug naar blog na editten

/local/ousearch/styles.css
Regel 31: Verwijder zoek knopje

/mod/ouwiki/

