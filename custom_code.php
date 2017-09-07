<?php 
ini_set('display_errors', 1);
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
//ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once('app/Mage.php');
umask(0);
Mage::app('');
Mage::setIsDeveloperMode(true);
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database
 // $siteBaseUrl = Mage::helper('function/more')->getBhishoomSiteBaseUrl();
 // echo $siteBaseUrl; exit;

// $date = Mage::getModel('core/date')->date('Y-M-d');
// $date = (explode('-',$date));
// $date =  $date[1].','.$date[2].' '.$date[0];   
// echo $date; exit;

$sku = array("boombox-case","multicolor-triangle-pattern-case-grp12","meditation-case","flowers-on-wood-pattern-case-grp12","super-nalayak-case-grp12","radhe-krishna-case","art-on-wood-case-grp12","jindagi-jhandwa-case-grp12","beer-mug-case","retro-audio-cassette-case","virat-18-new-case-grp12","sachin-10-case-grp12","owls-wala-love-case","retro-cassette-player-case","united-states-of-america-case","abstract-art-pattern-case-grp12","artistic-dream-catcher-case-grp12","retro-black-camera-case-grp12","geometric-pattern-case-grp12","mard-ki-moonch-case-grp12","glittering-gold-case-grp12","elegant-floral-pattern-case","gym-na-ho-payega-case-grp12","3d-pug-case","golden-nail-paint-case","blue-elephant-case-grp12","dream-ferrari-case-grp12","grey-metallic-pattern-case-grp12","i-am-drunk-case-grp12","super-namo-case-grp12","torn-superman-tee-case-grp12","zebra-n-flower-case","blue-concrete-case-grp12","gogo-ka-gussa-case-grp12","match-box-case","owlets-homeland-case","morpankh-case","modern-cat-case-grp12","no-girlfriend-no-tension-case-grp12","i-kiss-better-then-i-cook-case-grp12","washing-machine-case","swagger-case-grp12","apple-abstract-case-grp12","what's-up-man-case-grp12","colorful-wood-case-grp12","white-&-blue-texture-case-grp12","gothic-jacket-case","aaj-se-daaru-band-case-grp12","drink-pee-repeat-case-grp12","hearts-pattern-case","abstract-triangles-case-grp12","black-lamborghini-case-grp12","apne-baap-ko-mat-sikha-case-grp12","artistic-wooden-pattern-case-grp12","iron-man-armour-case-grp12","multicolor-lines-case-grp12","battery-holder-case","soccor-field-case","royal-marble-case-grp12","pixelated-dil-case","video-game-controller-case","khamosh-case-grp12","fighter-tank-case","kitty-party-case","multicolor-wooden-plank-case","look-with-affection-case","meri-pahunch-case","retro-world-map-case-grp12","chipset-case","i-got-the-power-case","retro-radio-case","tau-ki-baat-ho-ri-sey-case","you-are-so-sweet-case-grp12","ronaldo-7-case-grp12","brown-door-case-grp12","hud-dabangg-case","oh-ferrari-case","retro-india-flag-case","3d-apple-metal-case-grp12","serene-beach-case","big-stripes-pattern-case","3d-pencil-drawing-case-grp12","hai-la-case-grp12","oh-shit-case-grp12","david-bekham-case","eiffel-tower-case","floppy-disk-case","how-am-i-looking-case","brown-wooden-plank-case","old-audio-cassette-case","hass-mat-pagli-case","heart-flowers-case","trumphys-wedding-case","blue-door-case-grp12","retro-painted-wood-case-grp12","very-strong-girl-case-grp12","retro-american-flag-case-grp12","sachin-10-new-case-grp12","virat-18-case-grp12","milk-chocolate-case","bar-o-licious-case","ashtronishing-case","achieve-your-goal-case-grp12","basketball-court-case","artistic-texture-case","achha-lagta-hai-case-grp12","iphone-at-back-case","dripping-strips-case-grp12","cheetah-pattern-pink-case","#sexy-case-grp12","cute-dowl-case","switchboard-case","telephone-booth-case","two-avatars-case","rugged-wooden-texture-case-grp12","air-conditioner-case","abstract-rangoli-case","black-&-blue-leather-texture-case-grp12","absurdity-case","bold-stripes-pattern-case","red-narrow-stripes-pattern-case","be-the-mard-case-grp12","i-m-responsible-case","super-sparrow-league-case","joint-effort-case-grp12","exotic-rangoli-case","kuch-meetha-ho-jaaye-case","lego-toys-case","maroon-door-case-grp12","colourful-zig-zag-pattern-case","dumble-rumble-case","retro-phone-booth-case","haathi-mere-saathi-case","jerk-case-grp12","green-goa-case-grp12","nope-case-grp12","white-&-green-texture-case-grp12","pug-in-a-bag-case-grp12","super-bahubali-case-grp12","blue-zig-zag-pattern-case","red-concrete-case-grp12","gaali-kisne-di-case-grp12","hulkat-case-grp12","balaji-tilak-case","heart-doodle-case","horn-please-case","green-emarald-case","kya-totta-hai-yaar-case-grp12","new-day-case-grp12","off-&-on-case-grp12","joint-family-case-grp12","i-love-london-case","super-moustache-case-grp12","home-alone-case","biker-boy-case-grp12","bob-maar-le-case-grp12","just-did-it-case-grp12","lol-case-grp12","super-besharam-case-grp12","2-wheel-classic-case","football-fever-case","heart-pixels-case","military-green-pattern-case","pig-in-the-city-case","vintage-sports-car-case","virat-kohli-jersy-case","black-&-maroon-leather-texture-case-grp12","torn-batman-tee-case-grp12","after-party-case-grp12","handsome-chap-case-grp12","super-chirkoot-case-grp12","hipster-case","ronaldo-brazil-case","hat-budbak-case-grp12","i-need-space-case-grp12","pop-boombox-case-grp12","macho-man-case","three-sisters-case-grp12","brown-armour-jacket-case","blue-moroccan-pattern-case","blue-narrow-stripes-pattern-case","blah-case-grp12","drunk-as-shit-case-grp12","i-train-superheroes-case-grp12","futuristic-machine-case","ha-thor-aa-case-grp12","key-tade-hai-case-grp12","keep-calm-and-drink-case","jungle-book-case","hanging-out-case","do-not-pee-here-case","leaf-on-wood-case-grp12","luv-weekends-case","meow-case-grp12","buri-nazar-mat-daal-case","blue-&-gold-marble-case-grp12","little-becomes-a-lot-case","owl-forest-case","pop-cycle-case","red-&-white-road-case-grp12","retro-video-cassete-case","cool-moustache-case","sada-sexy-raho-case-grp12","my-daily-routine-case","omy-sweetie-case","i'm-not-weird-case-grp12","torn-avengers-tee-case-grp12","gems-case-grp12","bagwantis-case","zinedine-zidane-case","born-intelligent-case-grp12","dhoni-case","classic-retro-cars-case-grp12","heart-connection-case","hat-jhutthi-case-grp12","kya-lega-case-grp12","panda-love-case-grp12","high-5-case","hatbay-case-grp12","talli-case-grp12","power-board-case","burger-king-case-grp12","muscles-loading-case-grp12","keep-it-real-case","pyar-se-dekho-case","only-for-you-case","let's-make-babies-case-grp12","knight-bear-case","knight-wolf-case","killin-it-case-grp12","chill-bro-case-grp12","colorful-buddha-case","dabangg-vader-case-grp12","pretty-drunk-case-grp12","cheetah-pattern-red-case","sexy-inside-case-grp12","white-marble-case-grp12","thoranium-case","u-turn-me-on-case-grp12","who-am-i-case-grp12","wood-&-wall-pattern-case-grp12","astronaut-monkey-case-grp12","genius-hoon-case-grp12","mere-do-anmol-ratan-case-grp12","chemical-x-case","pausetive-case","cool-fox-case-grp12","donate-blood-case-grp12","i-train-your-trainer-case-grp12","pink-zig-zag-pattern-case","pixel-moroccan-pattern-case","race-with-me-case-grp12","red-&-grey-pattern-case-grp12","zindagi-jhand-ho-hayi-hai-case-grp12","yellow-&-blue-leather-texture-case-grp12","tajmahal-case","kiss-me-case","don't-teach-daddy-case-grp12","war-time-case-grp12","lets-connect-case","life-is-simple-case","joint-venture-case-grp12","lip-service-case","multicolour-plank-case","motherboard-case","music-all-night-case-grp12","saiko-case-grp12","sexsi-case-grp12","grumpy-cat-case","karam-kar-case","london-bus-case","snakes-ladders-case","i-do-case-grp12","what-the-fuck-case-grp12","basketball-case","for-hire-case","funinthesun-case","kisses-with-love-case","lets-make-love-case","palm-beach-case","spiderman-effect-case","lionel-messi-10-case","no-means-no-case-grp12","rajasthani-door-art-case-grp12","air-o-plane-case","desi-case-grp12","game-boy-case","ghanta-engineer-case-grp12","golden-marble-case-grp12","hello-namaaste-case-grp12","i-love-my-boy-friend-case-grp12","red-sunglasses-case-grp12","shiva-case","truth-over-lie-case","sahi-pakde-hai-case-grp12","i'm-that-boy-case-grp12","gadar-handpump-case","life-is-good-case","vintage-clock-case","4-bottle-vodka-case","awesome-in-bed-case-grp12","bal-gopal-case","bal-krishna-case","best-of-luck-case-grp12","genius-minds-case","f.b.i.-case-grp12","football-frenzy-case","fck-off-case-grp12","fried-ya-case-grp12","follow-me-case-grp12","brown-marble-case-grp12","chad-gayi-hai-case-grp12","coffee-time-case","cheeze-badi-hai-mast-case-grp12","dark-side-se-panga-case-grp12","eat-sleep-gym-case","indian-heritage-case-grp12","mahi-7-case-grp12","make-it-neat-case-grp12","tantrik-owl-case-grp12","x-man-wolf-case-grp12","yoga-o-holics-case","stay-cool-case-grp12","tamanna-case-grp12","synthesizer-case","crush-roll-smoke-case-grp12","kaunwa-gola-ke-ho-case-grp12","ram-pyari-ki-chai-case-grp12","virgin-bhaijaan-case-grp12","chalaak-lomdi-case","its-not-my-fault-case","sachin-tendulkar-case","sansani-case","you-sexy-thing-case","shit-happens-case","scan-me-case-grp12","strips-of-hearts-case","stop-staring-me-case-grp12","broken-glass-case","f.k.u.-case-grp12","ghanta-case","shortage-of-time-case","psycho-case-grp12","white-sunglasses-case-grp12","baby-in-a-carrier-case-grp12","bar-friends-case-grp12","colourful-shades-case-grp12","big-ben-case","colourful-tee-case","goten-ball-z-case","its-star-wars-case","dhoni-7-case-grp12","leopard-skin-case","lip-service-case-grp12","rap-chick-case-grp12","astranauts-imagination-case","no-smoking-please-case","sexy-brain-case","lo-khada-ho-gaya-case-grp12","make-days-count-case-grp12","macho-case-grp12","wah-taj-case","make-in-india-case-grp12","sai-baba-case","mind-your-language-case","music-in-the-air-case-grp12","myself-majnu-case-grp12","naam-toh-suna-hoga-case-grp12","namo-case-grp12","i-am-single-case","don't-fool-around-case-grp12","party-chick-case-grp12","ph.d-case-grp12","she-is-your-bhabhi-case-grp12","david-luiz-case","point-hai-baat-me-case","whats-up-parker-case","don't-challenge-bihari-case-grp12","ittu-sa-tha-case-grp12","ladaku-chokre-case-grp12","let's-hook-up-case-grp12","you-are-a-bomb-case-grp12","4-bottle-mocktail-case","give-it-up-case","shoes-booze-and-tattoos-case","swiss-cow-case","angry-avenger-case","be-yourself-case","black-marble-case-grp12","black-magic-case","better-on-facebook-case-grp12","coffee-beans-case-grp12","borrow-a-kiss-case-grp12","body-parts-case","chal-daru-pite-hain-case","lets-clean-india-case-grp12","fart-avengers-case","sexy-munda-case-grp12","shave-the-world-case","vintage-camera-case","kunwara-case-grp12","kuwara-aadmi-case","can-you-help-me-case-grp12","kya-ukhad-lega-case-grp12","game-over-case","make-love-not-babies-case-grp12","shut-your-mouth-case-grp12","dont-say-it-case","i-piss-awesomeness-case-grp12","high-heels-case-grp12","i'm-a-supergirl-case-grp12","you-and-i-case","yoga-se-hoga-case","your-workout-is-my-warmup-case-grp12","what-is-mobile-number-case","disobey-case-grp12","virat-kohli-case","vintage-stamps-case","watt-lag-gayi-case-grp12","i-m-possible-case","hello-awesome-case-grp12","what's-cooking-case-grp12","world-map-on-wood-case-grp12","try-hard-case-grp12","its-dragon-ball-z-case","tronelicious-case","own-me-if-u-can-case","whats-up-son-case","tuxedo-case-grp12","torn-captain-america-tee-case-grp12","vintage-switchboard-case","work-smart-case-grp12","work-hard-get-lucky-case","mein-virgin-hoon-case-grp12","orgasm-donor-case-grp12","run-case-grp12","stay-away-case-grp12","tanki-hai-saala-case-grp12","3-idiot-test-case","no-smoking-case","super-haramkhor-case-grp12","super-mahapurush-case-grp12","success-code-case","teri-kehke-loonga-case-grp12","that's-my-spot-case-grp12","skull-and-bones-case","think-different-case","ashleel-launda-case-grp12","blue-nissan-case","cute-messi-case","dancing-cats-case-grp12","buddha-art-case","mario-video-game-case","exotic-beach-resort-case","goddess-lakshmi-case","gogo-ka-badla-case-grp12","green-concrete-case-grp12","haramkhor-case","i-am-not-drunk-case-grp12","i-know-you-case-grp12","i-look-better-in-towel-case-grp12","bas-kar-bhai-case","i-love-goa-case-grp12","ilaka-tera-dhamaka-mera-case-grp12","namokar-case-grp12","ooi-ma-case-grp12","pen-is-strong-case-grp12","mr-teacher-case","peene-ka-bahana-case","retro-brazil-flag-case","round-is-a-shape-case-grp12","rum-only-case","tiger-skin-case","answer-gods-call-case","baby-making-machine-case-grp12","bawli-booch-se-kay-case-grp12","baba-jis-thenga-case","aukat-main-rahio-case-grp12","battery-charger-case","creative-director-case","golden-armour-jacket-case","just-dont-care-case","green-texture-marble-case-grp12","9-two-11-case-grp12","grey-beauty-case","grey-boombox-case","grow-a-moustache-case","hi-case-grp12","pagla-gaye-kya-case-grp12","pagla-case-grp12","oye-hoye-case-grp12","padai-ke-side-effects-case","pink-nail-paint-case","playboy-case-grp12","sunset-at-beach-case","sunny-beach-case","the-flash-case","torn-spiderman-tee-case-grp12","wham-case-grp12","stop-following-me-case-grp12","pele-case","success-by-passion-case","kadki-chal-rahi-hai-case-grp12","thand-rakh-case-grp12","blink-if-you-want-me-case-grp12","tharki-case-grp12","kya-maal-hai-yaar-case-grp12","tirupati-balaji-case","unhappily-married-case","speed-o-meter-case","speedo-meter-case-grp12","tera-kya-hoga-kaaliya-case","tharki-chokro-case-grp12","star-band-case-grp12","six-case-grp12","u-suck-case-grp12","fight-club-case","retro-beauties-case","roadie-special-case","rocket-ronaldo-case","sexy-geek-case","sher-da-puttar-case","sone-bhi-do-yaaro-case","rooney-case","roadster-case","pangaa-na-lena-case-grp12","aaj-se-pakka-padenge-case-grp12","awara-hoon-case-grp12","atm-machine-case","absolutely-mine-case","h.t.m.l.-case-grp12","all-day-sport-case","allergic-case","bar-case","bedazzled-flower-case","force-it-case-grp12","hanging-camera-case-grp12","pie-pie-ka-hisaab-case-grp12","i-am-engeneer-case","its-daft-punk-case","neymar-jr-case","joint-antehem-case-grp12","kismat-ki-maar-case","lafanga-case-grp12","maja-ni-life-case-grp12","ladaku-gang-case-grp12","logic-hai-boss-case","kya-bolti-tu-case","microwave-case","maradona-10-case-grp12","mobile-motherboard-case","dhan-lakshmi-case","delhi-ka-launda-case-grp12","maalgaadi-case-grp12","mumbai-ka-launda-case-grp12","my-name-is-basanti-case","wahe-guru-ji-ka-khalsa-case","bhains-ki-taang-case-grp12","silver-microwave-case","rooney-10-case-grp12","bach-ke-rahiyo-case-grp12","deep-blue-concrete-case-grp12","colored-concrete-case-grp12","di-maria-case","designer-helmet-case","dont-tell-my-dad-case","retro-classic-car-case-grp12","retro-great-britain-flag-case","respect-case-grp12","panda-in-a-bag-case-grp12","red-ferrari-case","the-ocean-case","tonguelicious-case","tricolor-wood-case","shree-nath-ji-case","panga-mat-le-case","party-owl-case","omg-wtf-case","red-nail-paint-case","pow-case-grp12","python-skin-case","owen-case","jeep-love-case-grp12","select-start-case","ronaldinho-case","ronaldinho-10-case-grp12","mumbai-ki-laundi-case-grp12","lord-ganesha-case","its-king-kong-case","om-case-grp12","rancho-the-chimp-case","skyscrapers-case","phata-poster-nikla-tiger-case","mr-lawyer-case","mr-mba-case","mr-fashion-designer-case","crash-code-case-grp12","mr-engineer-case","dream-catcher-case-grp12","blue-fursby-case","dinner-with-batman-case","dj-frankenstein-case","ghani-angrej-na-ban-case-grp12","ghee-shakkar-case","hulk-style-case","njr-11-case-grp12","bhaag-bolt-case","bill-gates-case","black-danger-case","black-fox-case","i-luv-u-case","hello-zombie-case","hermoso-spain-case","ghanta-kuch-hoga-case-grp12","binary-rain-case","diamonds-and-me-case","black-panda-case-grp12","bora-case","alluring-london-case","a-night-at-venice-case","ball-z-case","i-love-my-girlfriend-case-grp12","i'm-that-girl-case-grp12","its-a-trap-mickey-case","kaali-maa-case","kaka-case","hug-me-case","humse-na-takrana-case-grp12","messi-case","king-of-the-jungle-case","multi-colour-floral-pattern-case","hey-ram-case","mr-doctor-case","mr-entrepreneur-case","dream-stuff-case","typewriter-case","exotic-greece-case","vintage-wood-case","muller-case","monster-dj-case","how-are-you-doing-case-grp12","i-can-see-everything-case","khallas-case","king-kong-case","stripes-cat-case","makeup-kit-case","ego-kaam-bigade-case-grp12","cover-me-up-case","calm-beach-case","black-beauty-case","dream-to-achive-case","drink-beer-save-water-case","being-salman-case-grp12","ghanta-mba-case-grp12","funky-zombie-case","michael-jordan-case","pati-patni-aur-mobile-case-grp12","do-ghoont-zindagi-ke-case-grp12","don't-be-negative-case-grp12","dream-resort-case","david-villa-case","beach-holiday-case","antique-face-mask-case","fundamental-rights-case-grp12","bubble-love-case","five-case-grp12","royality-of-the-jungle-case","drive-me-case-grp12","figo-case","are-you-a-keyword-case-grp12","champ-se-lise-case","delhi-ki-laundi-case-grp12","fck-you-case-grp12","shree-krishna-case","ferrari-case-grp12");

$position = array("1003","1026","1005","1008","1026","1012","1008","1004","1002","1021","1023","1013","1023","1005","1002","1016","1020","1009","1005","1017","1013","1026","1020","1013","1011","1020","1008","1022","1008","1016","1017","1005","1011","1016","1020","1007","1011","1013","1013","1021","1017","1017","1023","1001","1021","1008","1011","1011","1008","1013","1011","1016","1013","1020","1006","1004","1009","1016","1010","1006","1010","1006","1009","1004","1010","1016","1011","1020","1006","1026","1005","1023","1017","1011","1005","1013","1020","1028","1010","1013","1017","1023","1002","1021","1011","1002","1011","1011","1008","1007","1007","1021","1001","1018","1010","1015","1010","1016","1025","1021","1026","1015","1011","1020","1002","1023","1028","1017","1018","1017","1016","1007","1023","1007","1023","1006","1015","1004","1018","1007","1006","1004","1015","1015","1007","1008","1008","1026","1026","1003","1003","1004","1005","1020","1023","1001","1012","1010","1009","1008","1010","1012","1017","1010","1006","1021","1023","1005","1002","1012","1018","1010","1018","1010","1010","1010","1017","1007","1017","1007","1005","1020","1010","1012","1012","1015","1004","1003","1010","1012","1010","1021","1010","1017","1017","1004","1002","1007","1020","1018","1015","1011","1003","1005","1022","1026","1001","1005","1010","1023","1028","1006","1015","1023","1022","1008","1004","1015","1004","1007","1004","1020","1009","1023","1017","1010","1020","1009","1019","1017","1026","1007","1017","1017","1008","1008","1001","1012","1007","1020","1030","1013","1003","1017","1021","1004","1004","1003","1015","1012","1008","1012","1012","1012","1008","1004","1004","1026","1025","1017","1003","1003","1017","1004","1008","1017","1008","1015","1010","1017","1015","1017","1006","1007","1005","1021","1020","1015","1017","1015","1023","1012","1004","1020","1021","1015","1017","1015","1020","1013","1007","1018","1005","1002","1023","1024","1010","1018","1029","1025","1003","1008","1015","1017","1008","1018","1029","1008","1006","1013","1010","1026","1016","1020","1008");
// echo count ($sku);
// echo "<br>";
// echo count ($position);
// exit;
$resource = Mage::getSingleton('core/resource'); //get an instance of the core resource
 //get an instance of the write connection
$connection = $resource->getConnection('core_write');
$tableName = $resource->getTableName('catalog/category_product'); //this should add the prefix if you have on
foreach($sku as $skukey=>$skuname){


// print_r($position);exit;
//$_GET['sku']; //not really safe to read from $_GET but for test purposes it will do.
$id = Mage::getModel('catalog/product')->getIdBySku($skuname); //get the product id.

if(!$id)
continue;

  echo $sql = "UPDATE {$tableName} SET `position` = '".$position[$skukey]."' WHERE `product_id` = {$id}";

  //set the position to 0 for the product in all the categories.
 //run the query
$connection->query($sql);

}

/*

$product_sku = "tshirt-girls-sansani";
$product_id = Mage::getModel("catalog/product")->getIdBySku( $product_sku );
$product = Mage::getModel("catalog/product")->load( $product_id );
echo $product_name = $product->getName();echo '<br>';
echo $product_image = $product->getImageUrl();echo '<br>';
echo $product_code = $product->getDesignCode();
*/
/* $cat = Mage::getModel('catalog/category')->load(4);
$subcats = $cat->getChildren();
foreach(array_reverse(explode(',',$subcats)) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	
   echo $name."<br>";
  }
} */
/*

$menuStr = '';
$sessionCustomer =Mage::getSingleton("customer/session");
		if ($sessionCustomer->isLoggedIn()) {
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLoginUrl().'"><i class="mi mi-Login"></i>Login</a></li>';
		}	
			
			
		
		 else{ 
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLogoutUrl().'"><i class="mi mi-logout"></i>Logout</a></li>';
		 } 
/* $cat_id = 154;

$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true)->addAttributeToSort('position');


foreach ($collection as $cat) {
    echo $cat->getId().' '.'position'.  $cat->getPosition()."<br>";
} */

/* $cat_id = 154;
$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true);

foreach ($collection as $cat) {
    echo $cat->getId().' '.$cat->getPosition()."<br>";
} */
/*
$categoryId = 292;    
$products = Mage::getModel('catalog/category')->load($categoryId)
->getProductCollection()
->addAttributeToSelect('*')
->addAttributeToFilter(
    'status',
    array('eq' => Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
);
// echo "<pre>";
// print_r($products);
 foreach($products as $product){
	$productid =  $product->getId();
	 
	 $productt = Mage::getModel('catalog/product')->load($productid);
	 
	 //echo Mage::getModel('catalog/product_media_config')->getMediaUrl( $productt->getImage())."<br>";
	  echo $productt->getSku()."<br>";
	 
}
*/

 
 
 // echo "<pre>";           
 // print_r ($products);
  



// foreach ($collection as $product) {
    //get associated products (different sizes etc)
	//echo $product->getId();die;
    // $associatedProducts = Mage::getModel('catalog/product_type_configurable')->load($product->getId());

    // foreach ($associatedProducts as $simple) {
        //load product
        // echo $simple->getSku()."<br>";
		
		//$simple_product = Mage::getModel('catalog/product')->load($simple->getId());
		

        

    // }
// }


// foreach ($collection as $product){
   // $prod_id = $product->getSku();
    // echo $prod_id;
	// echo "<br>";
    
	




/*$ids = array("85726","85748","85749","85752","85756","85763","85767","85777","103274","103298","201740","85727","85739","85747","85750","85751","85757","85764","85768","85772","85778","85728","85758","85765","85769","85773","103275","201733","201734","85729","85738","85753","85759","85766","85774","103297","85730","85754","85760","85775","103276","201723","201735","85733","85737","85755","85761","85770","85776","85734","85762","85771","103277","201736","201739","85735","85741","201737","201738","85736","85740","85732","103278","85742","103270","85731","85743","85744","103271","85745","103272","85746","103273","103279","103280","103281","103282","103283","103284","103285","103286","103287","103288","103289","103290","103291","103292","103293","103294","103295","103296","201729","201730","201732","201745","201731");
*/


//print_r($prdIds);
/*foreach($prdIds as $product_id); {
 $obj = Mage::getModel('catalog/product');
 $_product = $obj->load($product_id);
 // get Product's name
 $productid = $_product->getId();
 
}

$loadpro=Mage::getModel('catalog/product')->load($productid);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($loadpro->getId());

foreach($mediaApiItems as $item){
$datatemp=$mediaApi->remove($loadpro->getId(), $item['file']);

}
$loadpro->save();*/
 // $catID = $current_category->getId(); //or any specific category id, e.g. 5
// $children = Mage::getModel('catalog/category')->getCategories($catID);
// foreach ($children as $category) {
       // echo $category->getId();
        // echo $category->getName();
       
// }
/*$categoryIds = Mage::getModel('catalog/product')->load(75); 
$productSku = array();
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	$prod_id = array(); 	
	foreach($collection as $product) 
	{
		//echo $prod_id = $product->getName();
		if(!in_array($product->getSku(),$productSku))
		$productSku[] = $product->getSku();
		//echo "<br>";
	}	
}
echo '<pre>';exit;
print_r($productSku);
exit;*/
/*$productId=85781;
$_product = Mage::getModel('catalog/product')->load($productId);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
try {
    $items = $mediaApi->items($_product->getId());
    foreach($items as $item) {
      
		$mediaApi->remove($_product->getId(), $item['file']);
	
    }
} catch (Exception $exception){
    var_dump($exception);
    die('Exception Thrown');
}*/


/*$productId=85779;
$product = Mage::getModel('catalog/product')->load($productId);
$fullImgPath = 'Add full path of the image that you want add';
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($product->getId());
$attributes = $product->getTypeInstance()->getSetAttributes();
$gallery = $attributes['media_gallery'];
if (count($mediaApiItems) > 0) {
    foreach($mediaApiItems as $item){
            
			$mediaApi->remove($product->getId(), $item['file']);
            //deleting image source
            if($gallery->getBackend()->getImage($product, $item['file'])) {
               $gallery->getBackend()->removeImage($product, $item['file']);
            }
    }
    $product->save();
}*/




 //before adding need to save product
// change mobile case model name
/*echo '<pre>';
$folder = 'E:\xampplite\htdocs\projects\Active\jazzy1\media\blank_images/';
//print_r($_SERVER);
$target = $folder.'mobile-case-name-change/';
$weeds = array('.', '..'); 
$directories = array_diff(scandir($target), $weeds);
$foldername = array_values($directories); 
//print_r($foldername);

$designername = array();
$lines = file($folder.'designer.csv');

foreach ($lines as $key => $value)
{
    $designername[$key] = ($value);
}
//print_r($designername);

$magento = array();
$lines = file($folder.'magento.csv');

foreach ($lines as $key => $value)
{
    $magento[$key] = ($value);
}
//print_r($magento);

for($i=0;$i<count($foldername);$i++)
{
	$name = trim($foldername[$i]);	
	for($j=0;$j<count($designername);$j++)
	{
		if(trim($designername[$j]) == $name)
		{	
			$new_name = trim($magento[$j]);
			echo $target.$name;
			echo '  =>  ';
			echo $target.$new_name;
			if($new_name != '')
			{
				$r = rename($target.$name, $target.$new_name);
			}
			if($r == 0)
			{
				$fp = fopen($folder."not_found.txt", "a");
				fwrite($fp, $new_name."\r\n");
			}
			echo ' <br> ';
		}
     }
    //echo $r;die;
}*/

// delete category from asus

// Mage::register("isSecureArea", 1);
// for($i=1980;$i<2914;$i++)
// {		
	// Mage::getModel("catalog/category")->load($i)->delete();
	// echo $i." delete";
	// echo '<br>';
// }

/*$cat_id = 34; // a category id that you can get from admin
//$category = Mage::getModel('catalog/category')->load($categoryId);

$category = Mage::getModel('catalog/category')->load($cat_id); //put your category id here
$child = $category->getChildren();
if($child != '')
{
	$children = explode(",", $child);
	for($i=0;$i<count($children);$i++)
	{
		$sub_cat = Mage::getModel('catalog/category')->load($children[$i]);
		//print_r($sub_cat);die;
		echo '<strong>'.$sub_cat->getName().'</strong>';
		echo '<br>';
		$sub_cat_child = $sub_cat->getChildren();
		if($sub_cat_child != '')
		{
			$sub_cat_children = explode(",", $sub_cat_child);
			for($j=0;$j<count($sub_cat_children);$j++)
			{
				$sub_sub_cat = Mage::getModel('catalog/category')->load($sub_cat_children[$j]);
				echo $sub_sub_cat->getName();
				echo '<br>';
			}
		}
	}
}*/

// custom option



//For case 
/*$categoryIds = array(34);		
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	//$prod_id = array(); 	
	foreach($collection as $product) 
	{
		$prod_id = $product->getId();
		$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
		$rowArray = $read->fetchRow($select); 
		$val_id = $rowArray['value_id'];
		if(isset($val_id) && $val_id != '')
		{
			$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area'))->where('value_id = '.$val_id);
			$select_val_row = $read->fetchRow($select_val); 
			$design_val = $select_val_row['design_area'];
			
				$write->beginTransaction();
				$fields = array();
				$fields['label'] = 'Front';
				$fields['position'] = 1;
				$fields['disabled'] = 0;
				$fields['color'] = 0;
				$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"7f9aUF5koApiVC31"}';
				$where = $write->quoteInto('value_id =?', $val_id);
				$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
				$write->commit();
			
		}
	}
	echo $categoryIds[$cat].' Complete';
	echo '<br>';
}*/



/*$prod_collection = Mage::getModel('catalog/category')->load(3);
$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
foreach($collection as $product)
{
	$pid = $product->getId();
	//echo $pid;die;
	$chk_option_qry = $read->query("SELECT `option_id` FROM `catalog_product_option` WHERE `product_id` = '".$pid."'");
	$chk_option_res = $chk_option_qry->fetch();
	if($chk_option_res['option_id'] == '')
	{
		//insert brand
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 1)");
		$last_boption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_boption_id."', 0, 'Brand')");	
			
		$brand_arr = array("Apple", "Samsung", "Google", "Htc", "Lg", "Motorola", "Sony", "Xiaomi", "Nokia", "Blackberry", "Asus", "Lenovo", "Oppo", "Gionee");
		for($i=0;$i<14;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_boption_id."', '', '".$i."')");
			$last_option_btype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_btype_id."', 0, '".$brand_arr[$i]."')");
		}
		
		// insert type
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 0)");
		$last_toption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_toption_id."', 0, 'Type')");	
				
		$type_arr = array("Mobile Case", "Mobile Skin");
		for($i=0;$i<2;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_toption_id."', '', '".$i."')");
			$last_option_ttype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_ttype_id."', 0, '".$type_arr[$i]."')");
		}
		
		// insert Model
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 2)");
		$last_moption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_moption_id."', 0, 'Model')");	
				
		$model_arr = array("iPhone 6","iPhone 5c","iPhone 4&4s","iPad Mini/Retina","iPad Air","iPad 4","iPhone 6 Plus","iPad 3","iPad 2","iPhone 5/5s","iPad","iPad Air 2","iPad Mini 2","iPod Touch 4","iPodTouch 5","Galaxy Grand Duos","Galaxy S Advanced","Galaxy S Duos","Galaxy S Duos 2 (S7582) & Trend Plus (S7580)","Galaxy S5","Galaxy S4 Mini","Galaxy S3","Galaxy Grand 2 G7106","Galaxy Grand I9080/I9082","Galaxy Mega 5.8","Galaxy Note 2 N7100","Galaxy Note 3","Galaxy Note 4","Galaxy S4","Galaxy S2","Galaxy S3 Mini","Galaxy S5 Mini","Galaxy Mega 6.3I9200","Galaxy Note 8.0","Galaxy Note 10.1","Galaxy Ace","Galaxy Ace Plus","Galaxy Ace 2","Galaxy Ace 3","Galaxy Ace 4","Galaxy Tab 2","Galaxy Tab3 7.0","Galaxy Tab3 8.0","GalaxyTab3 10.1","Galaxy Tab 7.0 Plus","Galaxy Tab 7.7","Galaxy Core","Galaxy Win","Samsung Trend 2 Duos","Galaxy Nexus","Galaxy Y","Galaxy Alpha","Galaxy Xcover 2","Lumia 620","Lumia 920","Lumia 520","BB Z10","BB Q10","BB 9900","Nexus 6","Nexus 5","Nexus 4","Nexus 7","HTC Desire 816","HTC One M7","HTC One M8","HTC One M8 Mini","HTC One S","HTC One X","HTC Sensation XL G21","Lg GoogleNexus 5","Lg Google Nexus 4","LG OPTIMUS G E975","LG OPTIMUS L5 II (E460)","LG G2","LG G3","LG L70","LG L90","Moto E","Moto G","Moto G2","Moto razr d1","Moto razr d3","Moto X","Moto X2","Moto Google Nexus 6","Xperia Sp","Xperia M2","Xperia Z","Xperia Z1","Xperia Z2","Xperia Z3","Xperia Z1 mini","XIAOM mi2","XIAOMI Redmi 1S","Google Nexus 7","Google Nexus 8","Google Nexus 9","Google Nexus 10");
		for($i=0;$i<84;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_moption_id."', '', '".$i."')");
			$last_option_mtype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_mtype_id."', 0, '".$model_arr[$i]."')");
		}
	}
	echo $pid." complete";
	echo '<br>';
	
}*/
?><?php 
ini_set('display_errors', 1);
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
//ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once('app/Mage.php');
umask(0);
Mage::app('');
Mage::setIsDeveloperMode(true);
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database
 // $siteBaseUrl = Mage::helper('function/more')->getBhishoomSiteBaseUrl();
 // echo $siteBaseUrl; exit;

// $date = Mage::getModel('core/date')->date('Y-M-d');
// $date = (explode('-',$date));
// $date =  $date[1].','.$date[2].' '.$date[0];   
// echo $date; exit;

$sku = array("boombox-case","multicolor-triangle-pattern-case-grp12","meditation-case","flowers-on-wood-pattern-case-grp12","super-nalayak-case-grp12","radhe-krishna-case","art-on-wood-case-grp12","jindagi-jhandwa-case-grp12","beer-mug-case","retro-audio-cassette-case","virat-18-new-case-grp12","sachin-10-case-grp12","owls-wala-love-case","retro-cassette-player-case","united-states-of-america-case","abstract-art-pattern-case-grp12","artistic-dream-catcher-case-grp12","retro-black-camera-case-grp12","geometric-pattern-case-grp12","mard-ki-moonch-case-grp12","glittering-gold-case-grp12","elegant-floral-pattern-case","gym-na-ho-payega-case-grp12","3d-pug-case","golden-nail-paint-case","blue-elephant-case-grp12","dream-ferrari-case-grp12","grey-metallic-pattern-case-grp12","i-am-drunk-case-grp12","super-namo-case-grp12","torn-superman-tee-case-grp12","zebra-n-flower-case","blue-concrete-case-grp12","gogo-ka-gussa-case-grp12","match-box-case","owlets-homeland-case","morpankh-case","modern-cat-case-grp12","no-girlfriend-no-tension-case-grp12","i-kiss-better-then-i-cook-case-grp12","washing-machine-case","swagger-case-grp12","apple-abstract-case-grp12","what's-up-man-case-grp12","colorful-wood-case-grp12","white-&-blue-texture-case-grp12","gothic-jacket-case","aaj-se-daaru-band-case-grp12","drink-pee-repeat-case-grp12","hearts-pattern-case","abstract-triangles-case-grp12","black-lamborghini-case-grp12","apne-baap-ko-mat-sikha-case-grp12","artistic-wooden-pattern-case-grp12","iron-man-armour-case-grp12","multicolor-lines-case-grp12","battery-holder-case","soccor-field-case","royal-marble-case-grp12","pixelated-dil-case","video-game-controller-case","khamosh-case-grp12","fighter-tank-case","kitty-party-case","multicolor-wooden-plank-case","look-with-affection-case","meri-pahunch-case","retro-world-map-case-grp12","chipset-case","i-got-the-power-case","retro-radio-case","tau-ki-baat-ho-ri-sey-case","you-are-so-sweet-case-grp12","ronaldo-7-case-grp12","brown-door-case-grp12","hud-dabangg-case","oh-ferrari-case","retro-india-flag-case","3d-apple-metal-case-grp12","serene-beach-case","big-stripes-pattern-case","3d-pencil-drawing-case-grp12","hai-la-case-grp12","oh-shit-case-grp12","david-bekham-case","eiffel-tower-case","floppy-disk-case","how-am-i-looking-case","brown-wooden-plank-case","old-audio-cassette-case","hass-mat-pagli-case","heart-flowers-case","trumphys-wedding-case","blue-door-case-grp12","retro-painted-wood-case-grp12","very-strong-girl-case-grp12","retro-american-flag-case-grp12","sachin-10-new-case-grp12","virat-18-case-grp12","milk-chocolate-case","bar-o-licious-case","ashtronishing-case","achieve-your-goal-case-grp12","basketball-court-case","artistic-texture-case","achha-lagta-hai-case-grp12","iphone-at-back-case","dripping-strips-case-grp12","cheetah-pattern-pink-case","#sexy-case-grp12","cute-dowl-case","switchboard-case","telephone-booth-case","two-avatars-case","rugged-wooden-texture-case-grp12","air-conditioner-case","abstract-rangoli-case","black-&-blue-leather-texture-case-grp12","absurdity-case","bold-stripes-pattern-case","red-narrow-stripes-pattern-case","be-the-mard-case-grp12","i-m-responsible-case","super-sparrow-league-case","joint-effort-case-grp12","exotic-rangoli-case","kuch-meetha-ho-jaaye-case","lego-toys-case","maroon-door-case-grp12","colourful-zig-zag-pattern-case","dumble-rumble-case","retro-phone-booth-case","haathi-mere-saathi-case","jerk-case-grp12","green-goa-case-grp12","nope-case-grp12","white-&-green-texture-case-grp12","pug-in-a-bag-case-grp12","super-bahubali-case-grp12","blue-zig-zag-pattern-case","red-concrete-case-grp12","gaali-kisne-di-case-grp12","hulkat-case-grp12","balaji-tilak-case","heart-doodle-case","horn-please-case","green-emarald-case","kya-totta-hai-yaar-case-grp12","new-day-case-grp12","off-&-on-case-grp12","joint-family-case-grp12","i-love-london-case","super-moustache-case-grp12","home-alone-case","biker-boy-case-grp12","bob-maar-le-case-grp12","just-did-it-case-grp12","lol-case-grp12","super-besharam-case-grp12","2-wheel-classic-case","football-fever-case","heart-pixels-case","military-green-pattern-case","pig-in-the-city-case","vintage-sports-car-case","virat-kohli-jersy-case","black-&-maroon-leather-texture-case-grp12","torn-batman-tee-case-grp12","after-party-case-grp12","handsome-chap-case-grp12","super-chirkoot-case-grp12","hipster-case","ronaldo-brazil-case","hat-budbak-case-grp12","i-need-space-case-grp12","pop-boombox-case-grp12","macho-man-case","three-sisters-case-grp12","brown-armour-jacket-case","blue-moroccan-pattern-case","blue-narrow-stripes-pattern-case","blah-case-grp12","drunk-as-shit-case-grp12","i-train-superheroes-case-grp12","futuristic-machine-case","ha-thor-aa-case-grp12","key-tade-hai-case-grp12","keep-calm-and-drink-case","jungle-book-case","hanging-out-case","do-not-pee-here-case","leaf-on-wood-case-grp12","luv-weekends-case","meow-case-grp12","buri-nazar-mat-daal-case","blue-&-gold-marble-case-grp12","little-becomes-a-lot-case","owl-forest-case","pop-cycle-case","red-&-white-road-case-grp12","retro-video-cassete-case","cool-moustache-case","sada-sexy-raho-case-grp12","my-daily-routine-case","omy-sweetie-case","i'm-not-weird-case-grp12","torn-avengers-tee-case-grp12","gems-case-grp12","bagwantis-case","zinedine-zidane-case","born-intelligent-case-grp12","dhoni-case","classic-retro-cars-case-grp12","heart-connection-case","hat-jhutthi-case-grp12","kya-lega-case-grp12","panda-love-case-grp12","high-5-case","hatbay-case-grp12","talli-case-grp12","power-board-case","burger-king-case-grp12","muscles-loading-case-grp12","keep-it-real-case","pyar-se-dekho-case","only-for-you-case","let's-make-babies-case-grp12","knight-bear-case","knight-wolf-case","killin-it-case-grp12","chill-bro-case-grp12","colorful-buddha-case","dabangg-vader-case-grp12","pretty-drunk-case-grp12","cheetah-pattern-red-case","sexy-inside-case-grp12","white-marble-case-grp12","thoranium-case","u-turn-me-on-case-grp12","who-am-i-case-grp12","wood-&-wall-pattern-case-grp12","astronaut-monkey-case-grp12","genius-hoon-case-grp12","mere-do-anmol-ratan-case-grp12","chemical-x-case","pausetive-case","cool-fox-case-grp12","donate-blood-case-grp12","i-train-your-trainer-case-grp12","pink-zig-zag-pattern-case","pixel-moroccan-pattern-case","race-with-me-case-grp12","red-&-grey-pattern-case-grp12","zindagi-jhand-ho-hayi-hai-case-grp12","yellow-&-blue-leather-texture-case-grp12","tajmahal-case","kiss-me-case","don't-teach-daddy-case-grp12","war-time-case-grp12","lets-connect-case","life-is-simple-case","joint-venture-case-grp12","lip-service-case","multicolour-plank-case","motherboard-case","music-all-night-case-grp12","saiko-case-grp12","sexsi-case-grp12","grumpy-cat-case","karam-kar-case","london-bus-case","snakes-ladders-case","i-do-case-grp12","what-the-fuck-case-grp12","basketball-case","for-hire-case","funinthesun-case","kisses-with-love-case","lets-make-love-case","palm-beach-case","spiderman-effect-case","lionel-messi-10-case","no-means-no-case-grp12","rajasthani-door-art-case-grp12","air-o-plane-case","desi-case-grp12","game-boy-case","ghanta-engineer-case-grp12","golden-marble-case-grp12","hello-namaaste-case-grp12","i-love-my-boy-friend-case-grp12","red-sunglasses-case-grp12","shiva-case","truth-over-lie-case","sahi-pakde-hai-case-grp12","i'm-that-boy-case-grp12","gadar-handpump-case","life-is-good-case","vintage-clock-case","4-bottle-vodka-case","awesome-in-bed-case-grp12","bal-gopal-case","bal-krishna-case","best-of-luck-case-grp12","genius-minds-case","f.b.i.-case-grp12","football-frenzy-case","fck-off-case-grp12","fried-ya-case-grp12","follow-me-case-grp12","brown-marble-case-grp12","chad-gayi-hai-case-grp12","coffee-time-case","cheeze-badi-hai-mast-case-grp12","dark-side-se-panga-case-grp12","eat-sleep-gym-case","indian-heritage-case-grp12","mahi-7-case-grp12","make-it-neat-case-grp12","tantrik-owl-case-grp12","x-man-wolf-case-grp12","yoga-o-holics-case","stay-cool-case-grp12","tamanna-case-grp12","synthesizer-case","crush-roll-smoke-case-grp12","kaunwa-gola-ke-ho-case-grp12","ram-pyari-ki-chai-case-grp12","virgin-bhaijaan-case-grp12","chalaak-lomdi-case","its-not-my-fault-case","sachin-tendulkar-case","sansani-case","you-sexy-thing-case","shit-happens-case","scan-me-case-grp12","strips-of-hearts-case","stop-staring-me-case-grp12","broken-glass-case","f.k.u.-case-grp12","ghanta-case","shortage-of-time-case","psycho-case-grp12","white-sunglasses-case-grp12","baby-in-a-carrier-case-grp12","bar-friends-case-grp12","colourful-shades-case-grp12","big-ben-case","colourful-tee-case","goten-ball-z-case","its-star-wars-case","dhoni-7-case-grp12","leopard-skin-case","lip-service-case-grp12","rap-chick-case-grp12","astranauts-imagination-case","no-smoking-please-case","sexy-brain-case","lo-khada-ho-gaya-case-grp12","make-days-count-case-grp12","macho-case-grp12","wah-taj-case","make-in-india-case-grp12","sai-baba-case","mind-your-language-case","music-in-the-air-case-grp12","myself-majnu-case-grp12","naam-toh-suna-hoga-case-grp12","namo-case-grp12","i-am-single-case","don't-fool-around-case-grp12","party-chick-case-grp12","ph.d-case-grp12","she-is-your-bhabhi-case-grp12","david-luiz-case","point-hai-baat-me-case","whats-up-parker-case","don't-challenge-bihari-case-grp12","ittu-sa-tha-case-grp12","ladaku-chokre-case-grp12","let's-hook-up-case-grp12","you-are-a-bomb-case-grp12","4-bottle-mocktail-case","give-it-up-case","shoes-booze-and-tattoos-case","swiss-cow-case","angry-avenger-case","be-yourself-case","black-marble-case-grp12","black-magic-case","better-on-facebook-case-grp12","coffee-beans-case-grp12","borrow-a-kiss-case-grp12","body-parts-case","chal-daru-pite-hain-case","lets-clean-india-case-grp12","fart-avengers-case","sexy-munda-case-grp12","shave-the-world-case","vintage-camera-case","kunwara-case-grp12","kuwara-aadmi-case","can-you-help-me-case-grp12","kya-ukhad-lega-case-grp12","game-over-case","make-love-not-babies-case-grp12","shut-your-mouth-case-grp12","dont-say-it-case","i-piss-awesomeness-case-grp12","high-heels-case-grp12","i'm-a-supergirl-case-grp12","you-and-i-case","yoga-se-hoga-case","your-workout-is-my-warmup-case-grp12","what-is-mobile-number-case","disobey-case-grp12","virat-kohli-case","vintage-stamps-case","watt-lag-gayi-case-grp12","i-m-possible-case","hello-awesome-case-grp12","what's-cooking-case-grp12","world-map-on-wood-case-grp12","try-hard-case-grp12","its-dragon-ball-z-case","tronelicious-case","own-me-if-u-can-case","whats-up-son-case","tuxedo-case-grp12","torn-captain-america-tee-case-grp12","vintage-switchboard-case","work-smart-case-grp12","work-hard-get-lucky-case","mein-virgin-hoon-case-grp12","orgasm-donor-case-grp12","run-case-grp12","stay-away-case-grp12","tanki-hai-saala-case-grp12","3-idiot-test-case","no-smoking-case","super-haramkhor-case-grp12","super-mahapurush-case-grp12","success-code-case","teri-kehke-loonga-case-grp12","that's-my-spot-case-grp12","skull-and-bones-case","think-different-case","ashleel-launda-case-grp12","blue-nissan-case","cute-messi-case","dancing-cats-case-grp12","buddha-art-case","mario-video-game-case","exotic-beach-resort-case","goddess-lakshmi-case","gogo-ka-badla-case-grp12","green-concrete-case-grp12","haramkhor-case","i-am-not-drunk-case-grp12","i-know-you-case-grp12","i-look-better-in-towel-case-grp12","bas-kar-bhai-case","i-love-goa-case-grp12","ilaka-tera-dhamaka-mera-case-grp12","namokar-case-grp12","ooi-ma-case-grp12","pen-is-strong-case-grp12","mr-teacher-case","peene-ka-bahana-case","retro-brazil-flag-case","round-is-a-shape-case-grp12","rum-only-case","tiger-skin-case","answer-gods-call-case","baby-making-machine-case-grp12","bawli-booch-se-kay-case-grp12","baba-jis-thenga-case","aukat-main-rahio-case-grp12","battery-charger-case","creative-director-case","golden-armour-jacket-case","just-dont-care-case","green-texture-marble-case-grp12","9-two-11-case-grp12","grey-beauty-case","grey-boombox-case","grow-a-moustache-case","hi-case-grp12","pagla-gaye-kya-case-grp12","pagla-case-grp12","oye-hoye-case-grp12","padai-ke-side-effects-case","pink-nail-paint-case","playboy-case-grp12","sunset-at-beach-case","sunny-beach-case","the-flash-case","torn-spiderman-tee-case-grp12","wham-case-grp12","stop-following-me-case-grp12","pele-case","success-by-passion-case","kadki-chal-rahi-hai-case-grp12","thand-rakh-case-grp12","blink-if-you-want-me-case-grp12","tharki-case-grp12","kya-maal-hai-yaar-case-grp12","tirupati-balaji-case","unhappily-married-case","speed-o-meter-case","speedo-meter-case-grp12","tera-kya-hoga-kaaliya-case","tharki-chokro-case-grp12","star-band-case-grp12","six-case-grp12","u-suck-case-grp12","fight-club-case","retro-beauties-case","roadie-special-case","rocket-ronaldo-case","sexy-geek-case","sher-da-puttar-case","sone-bhi-do-yaaro-case","rooney-case","roadster-case","pangaa-na-lena-case-grp12","aaj-se-pakka-padenge-case-grp12","awara-hoon-case-grp12","atm-machine-case","absolutely-mine-case","h.t.m.l.-case-grp12","all-day-sport-case","allergic-case","bar-case","bedazzled-flower-case","force-it-case-grp12","hanging-camera-case-grp12","pie-pie-ka-hisaab-case-grp12","i-am-engeneer-case","its-daft-punk-case","neymar-jr-case","joint-antehem-case-grp12","kismat-ki-maar-case","lafanga-case-grp12","maja-ni-life-case-grp12","ladaku-gang-case-grp12","logic-hai-boss-case","kya-bolti-tu-case","microwave-case","maradona-10-case-grp12","mobile-motherboard-case","dhan-lakshmi-case","delhi-ka-launda-case-grp12","maalgaadi-case-grp12","mumbai-ka-launda-case-grp12","my-name-is-basanti-case","wahe-guru-ji-ka-khalsa-case","bhains-ki-taang-case-grp12","silver-microwave-case","rooney-10-case-grp12","bach-ke-rahiyo-case-grp12","deep-blue-concrete-case-grp12","colored-concrete-case-grp12","di-maria-case","designer-helmet-case","dont-tell-my-dad-case","retro-classic-car-case-grp12","retro-great-britain-flag-case","respect-case-grp12","panda-in-a-bag-case-grp12","red-ferrari-case","the-ocean-case","tonguelicious-case","tricolor-wood-case","shree-nath-ji-case","panga-mat-le-case","party-owl-case","omg-wtf-case","red-nail-paint-case","pow-case-grp12","python-skin-case","owen-case","jeep-love-case-grp12","select-start-case","ronaldinho-case","ronaldinho-10-case-grp12","mumbai-ki-laundi-case-grp12","lord-ganesha-case","its-king-kong-case","om-case-grp12","rancho-the-chimp-case","skyscrapers-case","phata-poster-nikla-tiger-case","mr-lawyer-case","mr-mba-case","mr-fashion-designer-case","crash-code-case-grp12","mr-engineer-case","dream-catcher-case-grp12","blue-fursby-case","dinner-with-batman-case","dj-frankenstein-case","ghani-angrej-na-ban-case-grp12","ghee-shakkar-case","hulk-style-case","njr-11-case-grp12","bhaag-bolt-case","bill-gates-case","black-danger-case","black-fox-case","i-luv-u-case","hello-zombie-case","hermoso-spain-case","ghanta-kuch-hoga-case-grp12","binary-rain-case","diamonds-and-me-case","black-panda-case-grp12","bora-case","alluring-london-case","a-night-at-venice-case","ball-z-case","i-love-my-girlfriend-case-grp12","i'm-that-girl-case-grp12","its-a-trap-mickey-case","kaali-maa-case","kaka-case","hug-me-case","humse-na-takrana-case-grp12","messi-case","king-of-the-jungle-case","multi-colour-floral-pattern-case","hey-ram-case","mr-doctor-case","mr-entrepreneur-case","dream-stuff-case","typewriter-case","exotic-greece-case","vintage-wood-case","muller-case","monster-dj-case","how-are-you-doing-case-grp12","i-can-see-everything-case","khallas-case","king-kong-case","stripes-cat-case","makeup-kit-case","ego-kaam-bigade-case-grp12","cover-me-up-case","calm-beach-case","black-beauty-case","dream-to-achive-case","drink-beer-save-water-case","being-salman-case-grp12","ghanta-mba-case-grp12","funky-zombie-case","michael-jordan-case","pati-patni-aur-mobile-case-grp12","do-ghoont-zindagi-ke-case-grp12","don't-be-negative-case-grp12","dream-resort-case","david-villa-case","beach-holiday-case","antique-face-mask-case","fundamental-rights-case-grp12","bubble-love-case","five-case-grp12","royality-of-the-jungle-case","drive-me-case-grp12","figo-case","are-you-a-keyword-case-grp12","champ-se-lise-case","delhi-ki-laundi-case-grp12","fck-you-case-grp12","shree-krishna-case","ferrari-case-grp12");

$position = array("501","502","503","504","504","505","506","507","507","508","509","509","510","511","512","513","514","514","515","515","516","516","517","517","518","519","519","519","519","520","520","521","522","523","523","525","526","527","528","529","530","531","532","532","533","533","533","535","535","535","536","536","536","537","538","538","538","538","539","539","539","540","540","540","541","542","543","544","544","545","545","545","546","547","549","549","549","549","550","550","552","553","554","555","555","555","555","555","556","556","557","558","558","559","559","559","560","560","560","561","562","564","566","566","567","568","568","569","569","570","570","570","570","570","571","572","573","574","575","576","576","577","577","577","578","578","578","578","579","579","580","580","581","582","585","586","587","588","588","588","589","589","589","589","590","590","591","593","594","596","597","598","599","599","600","600","600","600","600","600","600","600","600","600","600","600","602","602","603","605","607","608","609","610","610","610","610","611","614","615","616","617","618","620","620","621","622","622","624","625","626","627","628","629","629","630","630","630","631","632","633","634","635","637","639","640","640","642","643","644","645","647","648","649","650","650","650","650","651","652","653","655","656","656","658","659","660","660","660","661","663","664","665","666","668","669","670","670","671","672","673","674","676","677","678","678","679","680","681","682","683","684","685","686","687","688","689","690","690","690","691","693","694","695","696","697","699","699","699","699","699","699","700","700","700","700","700","700","700","700","700","701","702","704","706","707","708","709","710","711","711","711","711","711","712","712","712","714","716","718","719","720","721","726","727","730","731","732","733","734","735","736","737","738","739","740","741","741","743","744","745","746","747","748","749","750","750","750","750","750","750","750","750","750","751","752","753","754","757","759","760","760","764","765","767","768","769","770","770","774","776","779","779","780","780","780","780","780","783","786","787","788","790","790","791","792","794","795","796","797","799","799","799","799","799","799","799","800","800","800","800","800","800","800","800","800","802","803","804","805","806","807","807","808","809","810","810","811","811","811","813","814","815","815","818","820","820","820","822","823","824","825","826","827","827","830","831","833","834","834","835","836","837","838","838","839","840","840","841","843","846","848","849","850","850","850","850","850","850","850","851","852","853","855","856","857","857","859","860","861","862","863","866","868","869","870","872","873","874","875","876","877","878","879","880","880","880","880","881","882","884","885","890","892","893","894","895","896","897","898","899","899","900","900","902","903","904","905","906","907","908","909","911","912","915","916","917","918","919","920","920","921","922","922","931","933","935","936","937","939","940","941","942","943","945","946","949","950","951","952","956","957","958","959","960","966","967","968","970","971","972","972","973","974","975","977","978","979","980","980","980","981","981","982","983","984","988","989","989","990","991","992","993","996","998","999","999","1000","1002","1003","1004","1005","1006","1007","1008","1009","1010","1011","1014","1015","1015","1016","1017","1018","1019","1020","1020","1021","1023","1024","1025","1026","1027","1027","1028","1029","1030","1030","1035","1038","1039","1040","1041","1042","1043","1044","1045","1045","1046","1046","1047","1048","1050","1051","1052","1053","1054","1055","1056","1057","1057","1058","1059","1060","1071","1072","1073","1074","1075","1076","1077","1078","1079","1080","1081","1082","1083","1084","1085","1086","1087","1088","1089","1090","1091","1091","1092","1092","1093","1094","1095","1099","1100","1101","1102","1103","1104","1105","1106","1107","1108","1109","1110","1111","1120","1121","1123","1124","1125","1126","1127","1128","1129","1130","1131","1132","1134","1137","1138","1139","1140","1141","1142","1143","1149");
// echo count ($sku);
// echo "<br>";
// echo count ($position);
// exit;
$resource = Mage::getSingleton('core/resource'); //get an instance of the core resource
 //get an instance of the write connection
$connection = $resource->getConnection('core_write');
$tableName = $resource->getTableName('catalog/category_product'); //this should add the prefix if you have on
foreach($sku as $skukey=>$skuname){


// print_r($position);exit;
//$_GET['sku']; //not really safe to read from $_GET but for test purposes it will do.
$id = Mage::getModel('catalog/product')->getIdBySku($skuname); //get the product id.

if(!$id)
continue;

  echo $sql = "UPDATE {$tableName} SET `position` = '".$position[$skukey]."' WHERE `product_id` = {$id}";

  //set the position to 0 for the product in all the categories.
 //run the query
$connection->query($sql);

}

/*

$product_sku = "tshirt-girls-sansani";
$product_id = Mage::getModel("catalog/product")->getIdBySku( $product_sku );
$product = Mage::getModel("catalog/product")->load( $product_id );
echo $product_name = $product->getName();echo '<br>';
echo $product_image = $product->getImageUrl();echo '<br>';
echo $product_code = $product->getDesignCode();
*/
/* $cat = Mage::getModel('catalog/category')->load(4);
$subcats = $cat->getChildren();
foreach(array_reverse(explode(',',$subcats)) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	
   echo $name."<br>";
  }
} */
/*

$menuStr = '';
$sessionCustomer =Mage::getSingleton("customer/session");
		if ($sessionCustomer->isLoggedIn()) {
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLoginUrl().'"><i class="mi mi-Login"></i>Login</a></li>';
		}	
			
			
		
		 else{ 
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLogoutUrl().'"><i class="mi mi-logout"></i>Logout</a></li>';
		 } 
/* $cat_id = 154;

$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true)->addAttributeToSort('position');


foreach ($collection as $cat) {
    echo $cat->getId().' '.'position'.  $cat->getPosition()."<br>";
} */

/* $cat_id = 154;
$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true);

foreach ($collection as $cat) {
    echo $cat->getId().' '.$cat->getPosition()."<br>";
} */
/*
$categoryId = 292;    
$products = Mage::getModel('catalog/category')->load($categoryId)
->getProductCollection()
->addAttributeToSelect('*')
->addAttributeToFilter(
    'status',
    array('eq' => Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
);
// echo "<pre>";
// print_r($products);
 foreach($products as $product){
	$productid =  $product->getId();
	 
	 $productt = Mage::getModel('catalog/product')->load($productid);
	 
	 //echo Mage::getModel('catalog/product_media_config')->getMediaUrl( $productt->getImage())."<br>";
	  echo $productt->getSku()."<br>";
	 
}
*/

 
 
 // echo "<pre>";           
 // print_r ($products);
  



// foreach ($collection as $product) {
    //get associated products (different sizes etc)
	//echo $product->getId();die;
    // $associatedProducts = Mage::getModel('catalog/product_type_configurable')->load($product->getId());

    // foreach ($associatedProducts as $simple) {
        //load product
        // echo $simple->getSku()."<br>";
		
		//$simple_product = Mage::getModel('catalog/product')->load($simple->getId());
		

        

    // }
// }


// foreach ($collection as $product){
   // $prod_id = $product->getSku();
    // echo $prod_id;
	// echo "<br>";
    
	




/*$ids = array("85726","85748","85749","85752","85756","85763","85767","85777","103274","103298","201740","85727","85739","85747","85750","85751","85757","85764","85768","85772","85778","85728","85758","85765","85769","85773","103275","201733","201734","85729","85738","85753","85759","85766","85774","103297","85730","85754","85760","85775","103276","201723","201735","85733","85737","85755","85761","85770","85776","85734","85762","85771","103277","201736","201739","85735","85741","201737","201738","85736","85740","85732","103278","85742","103270","85731","85743","85744","103271","85745","103272","85746","103273","103279","103280","103281","103282","103283","103284","103285","103286","103287","103288","103289","103290","103291","103292","103293","103294","103295","103296","201729","201730","201732","201745","201731");
*/


//print_r($prdIds);
/*foreach($prdIds as $product_id); {
 $obj = Mage::getModel('catalog/product');
 $_product = $obj->load($product_id);
 // get Product's name
 $productid = $_product->getId();
 
}

$loadpro=Mage::getModel('catalog/product')->load($productid);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($loadpro->getId());

foreach($mediaApiItems as $item){
$datatemp=$mediaApi->remove($loadpro->getId(), $item['file']);

}
$loadpro->save();*/
 // $catID = $current_category->getId(); //or any specific category id, e.g. 5
// $children = Mage::getModel('catalog/category')->getCategories($catID);
// foreach ($children as $category) {
       // echo $category->getId();
        // echo $category->getName();
       
// }
/*$categoryIds = Mage::getModel('catalog/product')->load(75); 
$productSku = array();
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	$prod_id = array(); 	
	foreach($collection as $product) 
	{
		//echo $prod_id = $product->getName();
		if(!in_array($product->getSku(),$productSku))
		$productSku[] = $product->getSku();
		//echo "<br>";
	}	
}
echo '<pre>';exit;
print_r($productSku);
exit;*/
/*$productId=85781;
$_product = Mage::getModel('catalog/product')->load($productId);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
try {
    $items = $mediaApi->items($_product->getId());
    foreach($items as $item) {
      
		$mediaApi->remove($_product->getId(), $item['file']);
	
    }
} catch (Exception $exception){
    var_dump($exception);
    die('Exception Thrown');
}*/


/*$productId=85779;
$product = Mage::getModel('catalog/product')->load($productId);
$fullImgPath = 'Add full path of the image that you want add';
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($product->getId());
$attributes = $product->getTypeInstance()->getSetAttributes();
$gallery = $attributes['media_gallery'];
if (count($mediaApiItems) > 0) {
    foreach($mediaApiItems as $item){
            
			$mediaApi->remove($product->getId(), $item['file']);
            //deleting image source
            if($gallery->getBackend()->getImage($product, $item['file'])) {
               $gallery->getBackend()->removeImage($product, $item['file']);
            }
    }
    $product->save();
}*/




 //before adding need to save product
// change mobile case model name
/*echo '<pre>';
$folder = 'E:\xampplite\htdocs\projects\Active\jazzy1\media\blank_images/';
//print_r($_SERVER);
$target = $folder.'mobile-case-name-change/';
$weeds = array('.', '..'); 
$directories = array_diff(scandir($target), $weeds);
$foldername = array_values($directories); 
//print_r($foldername);

$designername = array();
$lines = file($folder.'designer.csv');

foreach ($lines as $key => $value)
{
    $designername[$key] = ($value);
}
//print_r($designername);

$magento = array();
$lines = file($folder.'magento.csv');

foreach ($lines as $key => $value)
{
    $magento[$key] = ($value);
}
//print_r($magento);

for($i=0;$i<count($foldername);$i++)
{
	$name = trim($foldername[$i]);	
	for($j=0;$j<count($designername);$j++)
	{
		if(trim($designername[$j]) == $name)
		{	
			$new_name = trim($magento[$j]);
			echo $target.$name;
			echo '  =>  ';
			echo $target.$new_name;
			if($new_name != '')
			{
				$r = rename($target.$name, $target.$new_name);
			}
			if($r == 0)
			{
				$fp = fopen($folder."not_found.txt", "a");
				fwrite($fp, $new_name."\r\n");
			}
			echo ' <br> ';
		}
     }
    //echo $r;die;
}*/

// delete category from asus

// Mage::register("isSecureArea", 1);
// for($i=1980;$i<2914;$i++)
// {		
	// Mage::getModel("catalog/category")->load($i)->delete();
	// echo $i." delete";
	// echo '<br>';
// }

/*$cat_id = 34; // a category id that you can get from admin
//$category = Mage::getModel('catalog/category')->load($categoryId);

$category = Mage::getModel('catalog/category')->load($cat_id); //put your category id here
$child = $category->getChildren();
if($child != '')
{
	$children = explode(",", $child);
	for($i=0;$i<count($children);$i++)
	{
		$sub_cat = Mage::getModel('catalog/category')->load($children[$i]);
		//print_r($sub_cat);die;
		echo '<strong>'.$sub_cat->getName().'</strong>';
		echo '<br>';
		$sub_cat_child = $sub_cat->getChildren();
		if($sub_cat_child != '')
		{
			$sub_cat_children = explode(",", $sub_cat_child);
			for($j=0;$j<count($sub_cat_children);$j++)
			{
				$sub_sub_cat = Mage::getModel('catalog/category')->load($sub_cat_children[$j]);
				echo $sub_sub_cat->getName();
				echo '<br>';
			}
		}
	}
}*/

// custom option



//For case 
/*$categoryIds = array(34);		
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	//$prod_id = array(); 	
	foreach($collection as $product) 
	{
		$prod_id = $product->getId();
		$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
		$rowArray = $read->fetchRow($select); 
		$val_id = $rowArray['value_id'];
		if(isset($val_id) && $val_id != '')
		{
			$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area'))->where('value_id = '.$val_id);
			$select_val_row = $read->fetchRow($select_val); 
			$design_val = $select_val_row['design_area'];
			
				$write->beginTransaction();
				$fields = array();
				$fields['label'] = 'Front';
				$fields['position'] = 1;
				$fields['disabled'] = 0;
				$fields['color'] = 0;
				$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"7f9aUF5koApiVC31"}';
				$where = $write->quoteInto('value_id =?', $val_id);
				$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
				$write->commit();
			
		}
	}
	echo $categoryIds[$cat].' Complete';
	echo '<br>';
}*/



/*$prod_collection = Mage::getModel('catalog/category')->load(3);
$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
foreach($collection as $product)
{
	$pid = $product->getId();
	//echo $pid;die;
	$chk_option_qry = $read->query("SELECT `option_id` FROM `catalog_product_option` WHERE `product_id` = '".$pid."'");
	$chk_option_res = $chk_option_qry->fetch();
	if($chk_option_res['option_id'] == '')
	{
		//insert brand
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 1)");
		$last_boption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_boption_id."', 0, 'Brand')");	
			
		$brand_arr = array("Apple", "Samsung", "Google", "Htc", "Lg", "Motorola", "Sony", "Xiaomi", "Nokia", "Blackberry", "Asus", "Lenovo", "Oppo", "Gionee");
		for($i=0;$i<14;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_boption_id."', '', '".$i."')");
			$last_option_btype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_btype_id."', 0, '".$brand_arr[$i]."')");
		}
		
		// insert type
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 0)");
		$last_toption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_toption_id."', 0, 'Type')");	
				
		$type_arr = array("Mobile Case", "Mobile Skin");
		for($i=0;$i<2;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_toption_id."', '', '".$i."')");
			$last_option_ttype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_ttype_id."', 0, '".$type_arr[$i]."')");
		}
		
		// insert Model
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 2)");
		$last_moption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_moption_id."', 0, 'Model')");	
				
		$model_arr = array("iPhone 6","iPhone 5c","iPhone 4&4s","iPad Mini/Retina","iPad Air","iPad 4","iPhone 6 Plus","iPad 3","iPad 2","iPhone 5/5s","iPad","iPad Air 2","iPad Mini 2","iPod Touch 4","iPodTouch 5","Galaxy Grand Duos","Galaxy S Advanced","Galaxy S Duos","Galaxy S Duos 2 (S7582) & Trend Plus (S7580)","Galaxy S5","Galaxy S4 Mini","Galaxy S3","Galaxy Grand 2 G7106","Galaxy Grand I9080/I9082","Galaxy Mega 5.8","Galaxy Note 2 N7100","Galaxy Note 3","Galaxy Note 4","Galaxy S4","Galaxy S2","Galaxy S3 Mini","Galaxy S5 Mini","Galaxy Mega 6.3I9200","Galaxy Note 8.0","Galaxy Note 10.1","Galaxy Ace","Galaxy Ace Plus","Galaxy Ace 2","Galaxy Ace 3","Galaxy Ace 4","Galaxy Tab 2","Galaxy Tab3 7.0","Galaxy Tab3 8.0","GalaxyTab3 10.1","Galaxy Tab 7.0 Plus","Galaxy Tab 7.7","Galaxy Core","Galaxy Win","Samsung Trend 2 Duos","Galaxy Nexus","Galaxy Y","Galaxy Alpha","Galaxy Xcover 2","Lumia 620","Lumia 920","Lumia 520","BB Z10","BB Q10","BB 9900","Nexus 6","Nexus 5","Nexus 4","Nexus 7","HTC Desire 816","HTC One M7","HTC One M8","HTC One M8 Mini","HTC One S","HTC One X","HTC Sensation XL G21","Lg GoogleNexus 5","Lg Google Nexus 4","LG OPTIMUS G E975","LG OPTIMUS L5 II (E460)","LG G2","LG G3","LG L70","LG L90","Moto E","Moto G","Moto G2","Moto razr d1","Moto razr d3","Moto X","Moto X2","Moto Google Nexus 6","Xperia Sp","Xperia M2","Xperia Z","Xperia Z1","Xperia Z2","Xperia Z3","Xperia Z1 mini","XIAOM mi2","XIAOMI Redmi 1S","Google Nexus 7","Google Nexus 8","Google Nexus 9","Google Nexus 10");
		for($i=0;$i<84;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_moption_id."', '', '".$i."')");
			$last_option_mtype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_mtype_id."', 0, '".$model_arr[$i]."')");
		}
	}
	echo $pid." complete";
	echo '<br>';
	
}*/
?><?php 
ini_set('display_errors', 1);
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
//ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once('app/Mage.php');
umask(0);
Mage::app('');
Mage::setIsDeveloperMode(true);
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database
 // $siteBaseUrl = Mage::helper('function/more')->getBhishoomSiteBaseUrl();
 // echo $siteBaseUrl; exit;

// $date = Mage::getModel('core/date')->date('Y-M-d');
// $date = (explode('-',$date));
// $date =  $date[1].','.$date[2].' '.$date[0];   
// echo $date; exit;

$sku = array("#sexy-grp5","9-two-11-grp5","aaj-se-daaru-band-grp5","aaj-se-pakka-padenge-grp5","abey-grp5","achha-lagta-hai-grp5","after-party-grp5","angreji-na-jhade-grp5","apne-baap-ko-mat-sikha-grp5","are-you-a-camera-grp5","are-you-a-compass-grp5","are-you-a-keyword-case-grp5","are-you-a-pataka-case-grp5","Ashleel- Launda-case-grp5","Astronaut- Monkey-case-grp5","Astronaut- Panda-case-grp5","Astronaut- Pug-case-grp5","Awara -Hoon-case-grp5","Awesome- In -Bed-case-grp5","Awesome-case-grp5","Baba- Ji- Ki- Booti-case-grp5","Babu- Bhaiya-case-grp5","Baby- In- A- Carrier-case-grp5","Baby- Making -Machine-case-grp5","Bach- Ke- Rahiyo-case-grp5","Back -To -The- Gym-case-grp5","Baklol-case-grp5","Bakwas- Band -Kar-case-grp5","Bar- Wars-case-grp5","Bas- Kar- Pagli-case-grp5","Bawli- Booch- Se- Kay-case-grp5","Be -The- Change-case-grp5","Beech -Me- Na- Pad-case-grp5","Being -Sallu-case-grp5","Being -Salman-case-grp5","Being -Stupid-case-grp5","Better- On- Facebook-case-grp5","Bhains- ki -taang-case-grp5","Biker- boy-case-grp5","Black- Dragon-case-grp5","Black- Panda-case-grp5","blah-case-grp5","blink-if-you-want-me-case-grp5","bob-maar-le-case-grp5","boom-case-grp5","born-intelligent-case-grp5","borrow-a-kiss-case-grp5","can-you-help-me-case-grp5","chad-gayi-hai-case-grp5","cheeze-badi-hai-mast-case-grp5","chill-bro-case-grp5","chill-with-me-case-grp5","classic-retro-cars-case-grp5","cmyk-cars-case-grp5","colorful-star-wars-case-grp5","colourful-shades-case-grp5","cool-story-babe-case-grp5","cool-story-brother-case-grp5","crash-code-case-grp5","crush-roll-smoke-case-grp5","cute-bus-case-grp5","dabangg-vader-case-grp5","dancing-cats-case-grp5","dark-side-se-panga-case-grp5","delhi-ka-launda-case-grp5","delhi-ki-laundi-grp5","desi-case-grp5","dhakkan-case-grp5","disobey-case-grp5","do-ghoont-zindagi-ke-case-grp5","donate-blood-case-grp5","don't-be-negative-case-grp5","don't-challenge-bihari--case-grp5","don't-teach-daddy-case-grp5","drink-pee-repeat-case-grp5","dripping-strips-case-grp5","drive-me-case-grp5","ego-kaam-bigade-case-grp5","f.b.i.-case-grp5","f.k.u.-case-grp5","fck-off-case-grp5","fck-you-case-grp5","force-it-case-grp5","fried-ya..!!-case-grp5","furrari-case-grp5","gaali-kisne-di-case-grp5","genius-hoon-case-grp5","get-drunk-case-grp5","ghani-angrej-na-ban-case-grp5","ghanta-engineer-case-grp5","ghanta-kuch-hoga-case-grp5","ghanta-mba-case-grp5","gogo-ka-badla-grp5","gogo-ka-gussa-case-grp5","green-goa-case-grp5","h.t.m.l.-case-grp5","hai-la-case-grp5","handsome-chap-case-grp5","hanging-camera-case-grp5","hangover-t-shirt-case-grp5","hari-putter-case-grp5","hata-sawan-ki-ghata-case-grp5","hatbay-case-grp5","hat-budbak-case-grp5","ha-thor-aa-case-grp5","hello-awesome-case-grp5","hello-namaaste-case-grp5","hi-case-grp5","hope-case-grp5","how-are-you-doing-case-grp5","hulkat-case-grp5","humse-na-takrana-case-grp5","i-am-a-player-case-grp5","i-am-drunk-case-grp5","i-am-not-drunk-case-grp5","i-am-sober-case-grp5","i-do-case-grp5","i-kiss-better-then-i-cook-case-grp5","i-know-you-case-grp5","ilaka-tera-dhamaka-mera-case-grp5","i-look--better-in--towel-case-grp5","i-love-goa-case-grp5","i-love-my-boy-friend-case-grp5","i-love-my-girlfriend-case-grp5","i'm-a-supergirl-case-grp5","i'm-available-case-grp5","i'm-not-weird-case-grp5","i'm-that-boy-case-grp5","i'm-that-girl-case-grp5","i-need-space-case-grp5","i-piss-awesomeness-case-grp5","i-train-superheroes-case-grp5","i-train-your-trainer-case-grp5","ittu-sa-tha-case-grp5","jeep-love-case-grp5","jerk-case-grp5","jindagi-jhandwa-case-grp5","joint-antehem-case-grp5","joint-effort-case-grp5","joint-family-case-grp5","joint-venture-case-grp5","just-did-it-case-grp5","kadki-chal-rahi-hai-case-grp5","kaunwa-gola-ke-ho-case-grp5","key-tade-hai-case-grp5","khamosh-case-grp5","khattak-case-grp5","killin'-it-case-grp5","krrish-fourth-case-grp5","kunwara-case-grp5","kya-lega-case-grp5","kya-maal-hai-yaar-case-grp5","kya-samja-case-grp5","kya-totta-hai-yaar-case-grp5","kya-ukhad-lega-case-grp5","ladaku-chokre-case-grp5","ladaku-gang-case-grp5","lafanga-case-grp5","laundi-case-grp5","lega-kya-case-grp5","lets-clean-india-case-grp5","let's-hook-up-case-grp5","let's-make-babies-case-grp5","lip-service-case-grp5","lo-khada-ho-gaya-case-grp5","lol-case-grp5","maalgaadi-case-grp5","macho-case-grp5","mahi-7-case-grp5","maja-ni-life-case-grp5","make-in-india-case-grp5","make-it-neat-case-grp5","make-love-not-babies-case-grp5","maradona-10-case-grp5","mard-ki-moonch-case-grp5","mein-virgin-hoon-case-grp5","mumbai-ka-launda-case-grp5","mumbai-ki-laundi-case-grp5","muscles-loading-case-grp5","music-in-the-air-case-grp5","myself-majnu-case-grp5","naam-toh-suna-hoga-case-grp5","nalayak-case-grp5","namastey-bitches-case-grp5","namo-case-grp5","namokar-case-grp5","njr-11-case-grp5","no-girlfriend-no-tension-case-grp5","no-means-no-case-grp5","off-&-on-case-grp5","omcase-grp5","ooi-ma-case-grp5","orgasm-donor-case-grp5","oye-hoye-case-grp5","pagalguy-case-grp5","pagla-case-grp5","pagla-gaye-kya-case-grp5","panda-in-a-bag-case-grp5","panda-love-case-grp5","pangaa-na-lena-case-grp5","party-chick-case-grp5","pati-patni-aur-mobile-case-grp5","pen-is-strong-case-grp5","personality-dekh-case-grp5","ph.d-case-grp5","pie-pie-ka-hisaab-case-grp5","playboy-case-grp5","pop-boombox-case-grp5","pop-cassettes-case-grp5","pop-lips-case-grp5","pow-case-grp5","pretty-drunk-case-grp5","psycho-case-grp5","pug-in-a-bag-case-grp5","race-with-me-case-grp5","ram-pyari-ki-chai-case-grp5","rap-chick-case-grp5","red-sunglasses-case-grp5","respect-case-grp5","retro-classic-car-case-grp5","ride-me-on-case-grp5","ronaldinho-10-case-grp5","ronaldo-7-case-grp5","rooney-10-case-grp5","round-is-a-shape-case-grp5","run-case-grp5","sachin-10-case-grp5","sada-sexy-raho-case-grp5","saiko-case-grp5","sanskari-babuji-case-grp5","scan-me-case-grp5","selfie-case-grp5","sexsi-case-grp5","sexy-bitch-case-grp5","sexy-inside-case-grp5","sexy-launda-case-grp5","sexy-laundi-case-grp5","sexy-munda-case-grp5","shattak-case-grp5","she-is-your-bhabhi-case-grp5","shut-your-mouth-case-grp5","star-band-case-grp5","stay-away-case-grp5","stay-cool-case-grp5","stop-following-me-case-grp5","stop-staring-me-case-grp5","super-bahubali-case-grp5","super-besharam-case-grp5","super-chirkoot-case-grp5","super-haramkhor-case-grp5","super-mahapurush-case-grp5","super-moustache-case-grp5","super-nalayak-case-grp5","super-namo-case-grp5","swagger-case-grp5","tanki-hai-saala-case-grp5","tantrik-owl-case-grp5","teri-kehke-loonga-case-grp5","thand-rakh-case-grp5","tharki-case-grp5","tharki-chokro-case-grp5","that's-my-spot-case-grp5","three-sisters-case-grp5","torn-avengers-tee-case-grp5","torn-batman-tee-case-grp5","torn-captain-america-tee-case-grp5","torn-spiderman-tee-case-grp5","torn-superman-tee-case-grp5","tuxedo-case-grp5","u-suck-case-grp5","u-turn-me-on-case-grp5","very-strong-girl-case-grp5","virat-18-case-grp5","virgin-bhaijaan-case-grp5","vodka-case-grp5","vrroooom-case-grp5","war-time-case-grp5","watt-lag-gayi-case-grp5","wearing-a-mirror-case-grp5","wham-case-grp5","what's-cooking-case-grp5","what's-up-man-case-grp5","what-the-fuck-case-grp5","white-sunglasses-case-grp5","who-am-i-case-grp5","work-smart-case-grp5","wow-case-grp5","x-man-wolf-case-grp5","yolo-case-grp5","you-are-a-bomb-case-grp5","you-are-cute-case-grp5","you-are-so-sweet-case-grp5","you-just-pissed-me-off-case-grp5","your-workout-is-my-warmup-case-grp5","zindagi-jhand-ho-hayi-hai-case-grp5"
,"zindagi-jhand-ho-hayi-hai-case-grp13");

$position = array("501","502","503","504","504","505","506","507","507","508","509","509","510","511","512","513","514","514","515","515","516","516","517","517","518","519","519","519","519","520","520","521","522","523","523","525","526","527","528","529","530","531","532","532","533","533","533","535","535","535","536","536","536","537","538","538","538","538","539","539","539","540","540","540","541","542","543","544","544","545","545","545","546","547","549","549","549","549","550","550","552","553","554","555","555","555","555","555","556","556","557","558","558","559","559","559","560","560","560","561","562","564","566","566","567","568","568","569","569","570","570","570","570","570","571","572","573","574","575","576","576","577","577","577","578","578","578","578","579","579","580","580","581","582","585","586","587","588","588","588","589","589","589","589","590","590","591","593","594","596","597","598","599","599","600","600","600","600","600","600","600","600","600","600","600","600","602","602","603","605","607","608","609","610","610","610","610","611","614","615","616","617","618","620","620","621","622","622","624","625","626","627","628","629","629","630","630","630","631","632","633","634","635","637","639","640","640","642","643","644","645","647","648","649","650","650","650","650","651","652","653","655","656","656","658","659","660","660","660","661","663","664","665","666","668","669","670","670","671","672","673","674","676","677","678","678","679","680","681","682","683","684","685","686","687","688","689","690","690","690","691","693","694","695","696","697","699","699","699","699","699","699","700","700","700","700","700","700","700","700","700","701","702","704","706","707","708","709","710","711","711","711","711","711","712","712","712","714","716","718","719","720","721","726","727","730","731","732","733","734","735","736","737","738","739","740","741","741","743","744","745","746","747","748","749","750","750","750","750","750","750","750","750","750","751","752","753","754","757","759","760","760","764","765","767","768","769","770","770","774","776","779","779","780","780","780","780","780","783","786","787","788","790","790","791","792","794","795","796","797","799","799","799","799","799","799","799","800","800","800","800","800","800","800","800","800","802","803","804","805","806","807","807","808","809","810","810","811","811","811","813","814","815","815","818","820","820","820","822","823","824","825","826","827","827","830","831","833","834","834","835","836","837","838","838","839","840","840","841","843","846","848","849","850","850","850","850","850","850","850","851","852","853","855","856","857","857","859","860","861","862","863","866","868","869","870","872","873","874","875","876","877","878","879","880","880","880","880","881","882","884","885","890","892","893","894","895","896","897","898","899","899","900","900","902","903","904","905","906","907","908","909","911","912","915","916","917","918","919","920","920","921","922","922","931","933","935","936","937","939","940","941","942","943","945","946","949","950","951","952","956","957","958","959","960","966","967","968","970","971","972","972","973","974","975","977","978","979","980","980","980","981","981","982","983","984","988","989","989","990","991","992","993","996","998","999","999","1000","1002","1003","1004","1005","1006","1007","1008","1009","1010","1011","1014","1015","1015","1016","1017","1018","1019","1020","1020","1021","1023","1024","1025","1026","1027","1027","1028","1029","1030","1030","1035","1038","1039","1040","1041","1042","1043","1044","1045","1045","1046","1046","1047","1048","1050","1051","1052","1053","1054","1055","1056","1057","1057","1058","1059","1060","1071","1072","1073","1074","1075","1076","1077","1078","1079","1080","1081","1082","1083","1084","1085","1086","1087","1088","1089","1090","1091","1091","1092","1092","1093","1094","1095","1099","1100","1101","1102","1103","1104","1105","1106","1107","1108","1109","1110","1111","1120","1121","1123","1124","1125","1126","1127","1128","1129","1130","1131","1132","1134","1137","1138","1139","1140","1141","1142","1143","1149");
// echo count ($sku);
// echo "<br>";
// echo count ($position);
// exit;
$resource = Mage::getSingleton('core/resource'); //get an instance of the core resource
 //get an instance of the write connection
$connection = $resource->getConnection('core_write');
$tableName = $resource->getTableName('catalog/category_product'); //this should add the prefix if you have on
foreach($sku as $skukey=>$skuname){


// print_r($position);exit;
//$_GET['sku']; //not really safe to read from $_GET but for test purposes it will do.
$id = Mage::getModel('catalog/product')->getIdBySku($skuname); //get the product id.

if(!$id)
continue;

  echo $sql = "UPDATE {$tableName} SET `position` = '".$position[$skukey]."' WHERE `product_id` = {$id}";

  //set the position to 0 for the product in all the categories.
 //run the query
$connection->query($sql);

}

/*

$product_sku = "tshirt-girls-sansani";
$product_id = Mage::getModel("catalog/product")->getIdBySku( $product_sku );
$product = Mage::getModel("catalog/product")->load( $product_id );
echo $product_name = $product->getName();echo '<br>';
echo $product_image = $product->getImageUrl();echo '<br>';
echo $product_code = $product->getDesignCode();
*/
/* $cat = Mage::getModel('catalog/category')->load(4);
$subcats = $cat->getChildren();
foreach(array_reverse(explode(',',$subcats)) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	
   echo $name."<br>";
  }
} */
/*

$menuStr = '';
$sessionCustomer =Mage::getSingleton("customer/session");
		if ($sessionCustomer->isLoggedIn()) {
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLoginUrl().'"><i class="mi mi-Login"></i>Login</a></li>';
		}	
			
			
		
		 else{ 
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLogoutUrl().'"><i class="mi mi-logout"></i>Logout</a></li>';
		 } 
/* $cat_id = 154;

$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true)->addAttributeToSort('position');


foreach ($collection as $cat) {
    echo $cat->getId().' '.'position'.  $cat->getPosition()."<br>";
} */

/* $cat_id = 154;
$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true);

foreach ($collection as $cat) {
    echo $cat->getId().' '.$cat->getPosition()."<br>";
} */
/*
$categoryId = 292;    
$products = Mage::getModel('catalog/category')->load($categoryId)
->getProductCollection()
->addAttributeToSelect('*')
->addAttributeToFilter(
    'status',
    array('eq' => Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
);
// echo "<pre>";
// print_r($products);
 foreach($products as $product){
	$productid =  $product->getId();
	 
	 $productt = Mage::getModel('catalog/product')->load($productid);
	 
	 //echo Mage::getModel('catalog/product_media_config')->getMediaUrl( $productt->getImage())."<br>";
	  echo $productt->getSku()."<br>";
	 
}
*/

 
 
 // echo "<pre>";           
 // print_r ($products);
  



// foreach ($collection as $product) {
    //get associated products (different sizes etc)
	//echo $product->getId();die;
    // $associatedProducts = Mage::getModel('catalog/product_type_configurable')->load($product->getId());

    // foreach ($associatedProducts as $simple) {
        //load product
        // echo $simple->getSku()."<br>";
		
		//$simple_product = Mage::getModel('catalog/product')->load($simple->getId());
		

        

    // }
// }


// foreach ($collection as $product){
   // $prod_id = $product->getSku();
    // echo $prod_id;
	// echo "<br>";
    
	




/*$ids = array("85726","85748","85749","85752","85756","85763","85767","85777","103274","103298","201740","85727","85739","85747","85750","85751","85757","85764","85768","85772","85778","85728","85758","85765","85769","85773","103275","201733","201734","85729","85738","85753","85759","85766","85774","103297","85730","85754","85760","85775","103276","201723","201735","85733","85737","85755","85761","85770","85776","85734","85762","85771","103277","201736","201739","85735","85741","201737","201738","85736","85740","85732","103278","85742","103270","85731","85743","85744","103271","85745","103272","85746","103273","103279","103280","103281","103282","103283","103284","103285","103286","103287","103288","103289","103290","103291","103292","103293","103294","103295","103296","201729","201730","201732","201745","201731");
*/


//print_r($prdIds);
/*foreach($prdIds as $product_id); {
 $obj = Mage::getModel('catalog/product');
 $_product = $obj->load($product_id);
 // get Product's name
 $productid = $_product->getId();
 
}

$loadpro=Mage::getModel('catalog/product')->load($productid);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($loadpro->getId());

foreach($mediaApiItems as $item){
$datatemp=$mediaApi->remove($loadpro->getId(), $item['file']);

}
$loadpro->save();*/
 // $catID = $current_category->getId(); //or any specific category id, e.g. 5
// $children = Mage::getModel('catalog/category')->getCategories($catID);
// foreach ($children as $category) {
       // echo $category->getId();
        // echo $category->getName();
       
// }
/*$categoryIds = Mage::getModel('catalog/product')->load(75); 
$productSku = array();
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	$prod_id = array(); 	
	foreach($collection as $product) 
	{
		//echo $prod_id = $product->getName();
		if(!in_array($product->getSku(),$productSku))
		$productSku[] = $product->getSku();
		//echo "<br>";
	}	
}
echo '<pre>';exit;
print_r($productSku);
exit;*/
/*$productId=85781;
$_product = Mage::getModel('catalog/product')->load($productId);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
try {
    $items = $mediaApi->items($_product->getId());
    foreach($items as $item) {
      
		$mediaApi->remove($_product->getId(), $item['file']);
	
    }
} catch (Exception $exception){
    var_dump($exception);
    die('Exception Thrown');
}*/


/*$productId=85779;
$product = Mage::getModel('catalog/product')->load($productId);
$fullImgPath = 'Add full path of the image that you want add';
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($product->getId());
$attributes = $product->getTypeInstance()->getSetAttributes();
$gallery = $attributes['media_gallery'];
if (count($mediaApiItems) > 0) {
    foreach($mediaApiItems as $item){
            
			$mediaApi->remove($product->getId(), $item['file']);
            //deleting image source
            if($gallery->getBackend()->getImage($product, $item['file'])) {
               $gallery->getBackend()->removeImage($product, $item['file']);
            }
    }
    $product->save();
}*/




 //before adding need to save product
// change mobile case model name
/*echo '<pre>';
$folder = 'E:\xampplite\htdocs\projects\Active\jazzy1\media\blank_images/';
//print_r($_SERVER);
$target = $folder.'mobile-case-name-change/';
$weeds = array('.', '..'); 
$directories = array_diff(scandir($target), $weeds);
$foldername = array_values($directories); 
//print_r($foldername);

$designername = array();
$lines = file($folder.'designer.csv');

foreach ($lines as $key => $value)
{
    $designername[$key] = ($value);
}
//print_r($designername);

$magento = array();
$lines = file($folder.'magento.csv');

foreach ($lines as $key => $value)
{
    $magento[$key] = ($value);
}
//print_r($magento);

for($i=0;$i<count($foldername);$i++)
{
	$name = trim($foldername[$i]);	
	for($j=0;$j<count($designername);$j++)
	{
		if(trim($designername[$j]) == $name)
		{	
			$new_name = trim($magento[$j]);
			echo $target.$name;
			echo '  =>  ';
			echo $target.$new_name;
			if($new_name != '')
			{
				$r = rename($target.$name, $target.$new_name);
			}
			if($r == 0)
			{
				$fp = fopen($folder."not_found.txt", "a");
				fwrite($fp, $new_name."\r\n");
			}
			echo ' <br> ';
		}
     }
    //echo $r;die;
}*/

// delete category from asus

// Mage::register("isSecureArea", 1);
// for($i=1980;$i<2914;$i++)
// {		
	// Mage::getModel("catalog/category")->load($i)->delete();
	// echo $i." delete";
	// echo '<br>';
// }

/*$cat_id = 34; // a category id that you can get from admin
//$category = Mage::getModel('catalog/category')->load($categoryId);

$category = Mage::getModel('catalog/category')->load($cat_id); //put your category id here
$child = $category->getChildren();
if($child != '')
{
	$children = explode(",", $child);
	for($i=0;$i<count($children);$i++)
	{
		$sub_cat = Mage::getModel('catalog/category')->load($children[$i]);
		//print_r($sub_cat);die;
		echo '<strong>'.$sub_cat->getName().'</strong>';
		echo '<br>';
		$sub_cat_child = $sub_cat->getChildren();
		if($sub_cat_child != '')
		{
			$sub_cat_children = explode(",", $sub_cat_child);
			for($j=0;$j<count($sub_cat_children);$j++)
			{
				$sub_sub_cat = Mage::getModel('catalog/category')->load($sub_cat_children[$j]);
				echo $sub_sub_cat->getName();
				echo '<br>';
			}
		}
	}
}*/

// custom option



//For case 
/*$categoryIds = array(34);		
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	//$prod_id = array(); 	
	foreach($collection as $product) 
	{
		$prod_id = $product->getId();
		$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
		$rowArray = $read->fetchRow($select); 
		$val_id = $rowArray['value_id'];
		if(isset($val_id) && $val_id != '')
		{
			$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area'))->where('value_id = '.$val_id);
			$select_val_row = $read->fetchRow($select_val); 
			$design_val = $select_val_row['design_area'];
			
				$write->beginTransaction();
				$fields = array();
				$fields['label'] = 'Front';
				$fields['position'] = 1;
				$fields['disabled'] = 0;
				$fields['color'] = 0;
				$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"7f9aUF5koApiVC31"}';
				$where = $write->quoteInto('value_id =?', $val_id);
				$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
				$write->commit();
			
		}
	}
	echo $categoryIds[$cat].' Complete';
	echo '<br>';
}*/



/*$prod_collection = Mage::getModel('catalog/category')->load(3);
$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
foreach($collection as $product)
{
	$pid = $product->getId();
	//echo $pid;die;
	$chk_option_qry = $read->query("SELECT `option_id` FROM `catalog_product_option` WHERE `product_id` = '".$pid."'");
	$chk_option_res = $chk_option_qry->fetch();
	if($chk_option_res['option_id'] == '')
	{
		//insert brand
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 1)");
		$last_boption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_boption_id."', 0, 'Brand')");	
			
		$brand_arr = array("Apple", "Samsung", "Google", "Htc", "Lg", "Motorola", "Sony", "Xiaomi", "Nokia", "Blackberry", "Asus", "Lenovo", "Oppo", "Gionee");
		for($i=0;$i<14;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_boption_id."', '', '".$i."')");
			$last_option_btype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_btype_id."', 0, '".$brand_arr[$i]."')");
		}
		
		// insert type
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 0)");
		$last_toption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_toption_id."', 0, 'Type')");	
				
		$type_arr = array("Mobile Case", "Mobile Skin");
		for($i=0;$i<2;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_toption_id."', '', '".$i."')");
			$last_option_ttype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_ttype_id."', 0, '".$type_arr[$i]."')");
		}
		
		// insert Model
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 2)");
		$last_moption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_moption_id."', 0, 'Model')");	
				
		$model_arr = array("iPhone 6","iPhone 5c","iPhone 4&4s","iPad Mini/Retina","iPad Air","iPad 4","iPhone 6 Plus","iPad 3","iPad 2","iPhone 5/5s","iPad","iPad Air 2","iPad Mini 2","iPod Touch 4","iPodTouch 5","Galaxy Grand Duos","Galaxy S Advanced","Galaxy S Duos","Galaxy S Duos 2 (S7582) & Trend Plus (S7580)","Galaxy S5","Galaxy S4 Mini","Galaxy S3","Galaxy Grand 2 G7106","Galaxy Grand I9080/I9082","Galaxy Mega 5.8","Galaxy Note 2 N7100","Galaxy Note 3","Galaxy Note 4","Galaxy S4","Galaxy S2","Galaxy S3 Mini","Galaxy S5 Mini","Galaxy Mega 6.3I9200","Galaxy Note 8.0","Galaxy Note 10.1","Galaxy Ace","Galaxy Ace Plus","Galaxy Ace 2","Galaxy Ace 3","Galaxy Ace 4","Galaxy Tab 2","Galaxy Tab3 7.0","Galaxy Tab3 8.0","GalaxyTab3 10.1","Galaxy Tab 7.0 Plus","Galaxy Tab 7.7","Galaxy Core","Galaxy Win","Samsung Trend 2 Duos","Galaxy Nexus","Galaxy Y","Galaxy Alpha","Galaxy Xcover 2","Lumia 620","Lumia 920","Lumia 520","BB Z10","BB Q10","BB 9900","Nexus 6","Nexus 5","Nexus 4","Nexus 7","HTC Desire 816","HTC One M7","HTC One M8","HTC One M8 Mini","HTC One S","HTC One X","HTC Sensation XL G21","Lg GoogleNexus 5","Lg Google Nexus 4","LG OPTIMUS G E975","LG OPTIMUS L5 II (E460)","LG G2","LG G3","LG L70","LG L90","Moto E","Moto G","Moto G2","Moto razr d1","Moto razr d3","Moto X","Moto X2","Moto Google Nexus 6","Xperia Sp","Xperia M2","Xperia Z","Xperia Z1","Xperia Z2","Xperia Z3","Xperia Z1 mini","XIAOM mi2","XIAOMI Redmi 1S","Google Nexus 7","Google Nexus 8","Google Nexus 9","Google Nexus 10");
		for($i=0;$i<84;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_moption_id."', '', '".$i."')");
			$last_option_mtype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_mtype_id."', 0, '".$model_arr[$i]."')");
		}
	}
	echo $pid." complete";
	echo '<br>';
	
}*/
?><?php 
ini_set('display_errors', 1);
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
//ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once('app/Mage.php');
umask(0);
Mage::app('');
Mage::setIsDeveloperMode(true);
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database
 // $siteBaseUrl = Mage::helper('function/more')->getBhishoomSiteBaseUrl();
 // echo $siteBaseUrl; exit;

// $date = Mage::getModel('core/date')->date('Y-M-d');
// $date = (explode('-',$date));
// $date =  $date[1].','.$date[2].' '.$date[0];   
// echo $date; exit;

$sku = array("#sexy-grp5","9-two-11-grp5","aaj-se-daaru-band-grp5","aaj-se-pakka-padenge-grp5","abey-grp5","achha-lagta-hai-grp5","after-party-grp5","angreji-na-jhade-grp5","apne-baap-ko-mat-sikha-grp5","are-you-a-camera-grp5","are-you-a-compass-grp5","are-you-a-keyword-case-grp5","are-you-a-pataka-case-grp5","Ashleel- Launda-case-grp5","Astronaut- Monkey-case-grp5","Astronaut- Panda-case-grp5","Astronaut- Pug-case-grp5","Awara -Hoon-case-grp5","Awesome- In -Bed-case-grp5","Awesome-case-grp5","Baba- Ji- Ki- Booti-case-grp5","Babu- Bhaiya-case-grp5","Baby- In- A- Carrier-case-grp5","Baby- Making -Machine-case-grp5","Bach- Ke- Rahiyo-case-grp5","Back -To -The- Gym-case-grp5","Baklol-case-grp5","Bakwas- Band -Kar-case-grp5","Bar- Wars-case-grp5","Bas- Kar- Pagli-case-grp5","Bawli- Booch- Se- Kay-case-grp5","Be -The- Change-case-grp5","Beech -Me- Na- Pad-case-grp5","Being -Sallu-case-grp5","Being -Salman-case-grp5","Being -Stupid-case-grp5","Better- On- Facebook-case-grp5","Bhains- ki -taang-case-grp5","Biker- boy-case-grp5","Black- Dragon-case-grp5","Black- Panda-case-grp5","blah-case-grp5","blink-if-you-want-me-case-grp5","bob-maar-le-case-grp5","boom-case-grp5","born-intelligent-case-grp5","borrow-a-kiss-case-grp5","can-you-help-me-case-grp5","chad-gayi-hai-case-grp5","cheeze-badi-hai-mast-case-grp5","chill-bro-case-grp5","chill-with-me-case-grp5","classic-retro-cars-case-grp5","cmyk-cars-case-grp5","colorful-star-wars-case-grp5","colourful-shades-case-grp5","cool-story-babe-case-grp5","cool-story-brother-case-grp5","crash-code-case-grp5","crush-roll-smoke-case-grp5","cute-bus-case-grp5","dabangg-vader-case-grp5","dancing-cats-case-grp5","dark-side-se-panga-case-grp5","delhi-ka-launda-case-grp5","delhi-ki-laundi-grp5","desi-case-grp5","dhakkan-case-grp5","disobey-case-grp5","do-ghoont-zindagi-ke-case-grp5","donate-blood-case-grp5","don't-be-negative-case-grp5","don't-challenge-bihari--case-grp5","don't-teach-daddy-case-grp5","drink-pee-repeat-case-grp5","dripping-strips-case-grp5","drive-me-case-grp5","ego-kaam-bigade-case-grp5","f.b.i.-case-grp5","f.k.u.-case-grp5","fck-off-case-grp5","fck-you-case-grp5","force-it-case-grp5","fried-ya..!!-case-grp5","furrari-case-grp5","gaali-kisne-di-case-grp5","genius-hoon-case-grp5","get-drunk-case-grp5","ghani-angrej-na-ban-case-grp5","ghanta-engineer-case-grp5","ghanta-kuch-hoga-case-grp5","ghanta-mba-case-grp5","gogo-ka-badla-grp5","gogo-ka-gussa-case-grp5","green-goa-case-grp5","h.t.m.l.-case-grp5","hai-la-case-grp5","handsome-chap-case-grp5","hanging-camera-case-grp5","hangover-t-shirt-case-grp5","hari-putter-case-grp5","hata-sawan-ki-ghata-case-grp5","hatbay-case-grp5","hat-budbak-case-grp5","ha-thor-aa-case-grp5","hello-awesome-case-grp5","hello-namaaste-case-grp5","hi-case-grp5","hope-case-grp5","how-are-you-doing-case-grp5","hulkat-case-grp5","humse-na-takrana-case-grp5","i-am-a-player-case-grp5","i-am-drunk-case-grp5","i-am-not-drunk-case-grp5","i-am-sober-case-grp5","i-do-case-grp5","i-kiss-better-then-i-cook-case-grp5","i-know-you-case-grp5","ilaka-tera-dhamaka-mera-case-grp5","i-look--better-in--towel-case-grp5","i-love-goa-case-grp5","i-love-my-boy-friend-case-grp5","i-love-my-girlfriend-case-grp5","i'm-a-supergirl-case-grp5","i'm-available-case-grp5","i'm-not-weird-case-grp5","i'm-that-boy-case-grp5","i'm-that-girl-case-grp5","i-need-space-case-grp5","i-piss-awesomeness-case-grp5","i-train-superheroes-case-grp5","i-train-your-trainer-case-grp5","ittu-sa-tha-case-grp5","jeep-love-case-grp5","jerk-case-grp5","jindagi-jhandwa-case-grp5","joint-antehem-case-grp5","joint-effort-case-grp5","joint-family-case-grp5","joint-venture-case-grp5","just-did-it-case-grp5","kadki-chal-rahi-hai-case-grp5","kaunwa-gola-ke-ho-case-grp5","key-tade-hai-case-grp5","khamosh-case-grp5","khattak-case-grp5","killin'-it-case-grp5","krrish-fourth-case-grp5","kunwara-case-grp5","kya-lega-case-grp5","kya-maal-hai-yaar-case-grp5","kya-samja-case-grp5","kya-totta-hai-yaar-case-grp5","kya-ukhad-lega-case-grp5","ladaku-chokre-case-grp5","ladaku-gang-case-grp5","lafanga-case-grp5","laundi-case-grp5","lega-kya-case-grp5","lets-clean-india-case-grp5","let's-hook-up-case-grp5","let's-make-babies-case-grp5","lip-service-case-grp5","lo-khada-ho-gaya-case-grp5","lol-case-grp5","maalgaadi-case-grp5","macho-case-grp5","mahi-7-case-grp5","maja-ni-life-case-grp5","make-in-india-case-grp5","make-it-neat-case-grp5","make-love-not-babies-case-grp5","maradona-10-case-grp5","mard-ki-moonch-case-grp5","mein-virgin-hoon-case-grp5","mumbai-ka-launda-case-grp5","mumbai-ki-laundi-case-grp5","muscles-loading-case-grp5","music-in-the-air-case-grp5","myself-majnu-case-grp5","naam-toh-suna-hoga-case-grp5","nalayak-case-grp5","namastey-bitches-case-grp5","namo-case-grp5","namokar-case-grp5","njr-11-case-grp5","no-girlfriend-no-tension-case-grp5","no-means-no-case-grp5","off-&-on-case-grp5","omcase-grp5","ooi-ma-case-grp5","orgasm-donor-case-grp5","oye-hoye-case-grp5","pagalguy-case-grp5","pagla-case-grp5","pagla-gaye-kya-case-grp5","panda-in-a-bag-case-grp5","panda-love-case-grp5","pangaa-na-lena-case-grp5","party-chick-case-grp5","pati-patni-aur-mobile-case-grp5","pen-is-strong-case-grp5","personality-dekh-case-grp5","ph.d-case-grp5","pie-pie-ka-hisaab-case-grp5","playboy-case-grp5","pop-boombox-case-grp5","pop-cassettes-case-grp5","pop-lips-case-grp5","pow-case-grp5","pretty-drunk-case-grp5","psycho-case-grp5","pug-in-a-bag-case-grp5","race-with-me-case-grp5","ram-pyari-ki-chai-case-grp5","rap-chick-case-grp5","red-sunglasses-case-grp5","respect-case-grp5","retro-classic-car-case-grp5","ride-me-on-case-grp5","ronaldinho-10-case-grp5","ronaldo-7-case-grp5","rooney-10-case-grp5","round-is-a-shape-case-grp5","run-case-grp5","sachin-10-case-grp5","sada-sexy-raho-case-grp5","saiko-case-grp5","sanskari-babuji-case-grp5","scan-me-case-grp5","selfie-case-grp5","sexsi-case-grp5","sexy-bitch-case-grp5","sexy-inside-case-grp5","sexy-launda-case-grp5","sexy-laundi-case-grp5","sexy-munda-case-grp5","shattak-case-grp5","she-is-your-bhabhi-case-grp5","shut-your-mouth-case-grp5","star-band-case-grp5","stay-away-case-grp5","stay-cool-case-grp5","stop-following-me-case-grp5","stop-staring-me-case-grp5","super-bahubali-case-grp5","super-besharam-case-grp5","super-chirkoot-case-grp5","super-haramkhor-case-grp5","super-mahapurush-case-grp5","super-moustache-case-grp5","super-nalayak-case-grp5","super-namo-case-grp5","swagger-case-grp5","tanki-hai-saala-case-grp5","tantrik-owl-case-grp5","teri-kehke-loonga-case-grp5","thand-rakh-case-grp5","tharki-case-grp5","tharki-chokro-case-grp5","that's-my-spot-case-grp5","three-sisters-case-grp5","torn-avengers-tee-case-grp5","torn-batman-tee-case-grp5","torn-captain-america-tee-case-grp5","torn-spiderman-tee-case-grp5","torn-superman-tee-case-grp5","tuxedo-case-grp5","u-suck-case-grp5","u-turn-me-on-case-grp5","very-strong-girl-case-grp5","virat-18-case-grp5","virgin-bhaijaan-case-grp5","vodka-case-grp5","vrroooom-case-grp5","war-time-case-grp5","watt-lag-gayi-case-grp5","wearing-a-mirror-case-grp5","wham-case-grp5","what's-cooking-case-grp5","what's-up-man-case-grp5","what-the-fuck-case-grp5","white-sunglasses-case-grp5","who-am-i-case-grp5","work-smart-case-grp5","wow-case-grp5","x-man-wolf-case-grp5","yolo-case-grp5","you-are-a-bomb-case-grp5","you-are-cute-case-grp5","you-are-so-sweet-case-grp5","you-just-pissed-me-off-case-grp5","your-workout-is-my-warmup-case-grp5","zindagi-jhand-ho-hayi-hai-case-grp5");

$position = array("1003","1026","1005","1008","1026","1012","1008","1004","1002","1021","1023","1013","1023","1005","1002","1016","1020","1009","1005","1017","1013","1026","1020","1013","1011","1020","1008","1022","1008","1016","1017","1005","1011","1016","1020","1007","1011","1013","1013","1021","1017","1017","1023","1001","1021","1008","1011","1011","1008","1013","1011","1016","1013","1020","1006","1004","1009","1016","1010","1006","1010","1006","1009","1004","1010","1016","1011","1020","1006","1026","1005","1023","1017","1011","1005","1013","1020","1028","1010","1013","1017","1023","1002","1021","1011","1002","1011","1011","1008","1007","1007","1021","1001","1018","1010","1015","1010","1016","1025","1021","1026","1015","1011","1020","1002","1023","1028","1017","1018","1017","1016","1007","1023","1007","1023","1006","1015","1004","1018","1007","1006","1004","1015","1015","1007","1008","1008","1026","1026","1003","1003","1004","1005","1020","1023","1001","1012","1010","1009","1008","1010","1012","1017","1010","1006","1021","1023","1005","1002","1012","1018","1010","1018","1010","1010","1010","1017","1007","1017","1007","1005","1020","1010","1012","1012","1015","1004","1003","1010","1012","1010","1021","1010","1017","1017","1004","1002","1007","1020","1018","1015","1011","1003","1005","1022","1026","1001","1005","1010","1023","1028","1006","1015","1023","1022","1008","1004","1015","1004","1007","1004","1020","1009","1023","1017","1010","1020","1009","1019","1017","1026","1007","1017","1017","1008","1008","1001","1012","1007","1020","1030","1013","1003","1017","1021","1004","1004","1003","1015","1012","1008","1012","1012","1012","1008","1004","1004","1026","1025","1017","1003","1003","1017","1004","1008","1017","1008","1015","1010","1017","1015","1017","1006","1007","1005","1021","1020","1015","1017","1015","1023","1012","1004","1020","1021","1015","1017","1015","1020","1013","1007","1018","1005","1002","1023","1024","1010","1018","1029","1025","1003","1008","1015","1017","1008","1018","1029","1008","1006","1013","1010","1026","1016","1020","1008");
// echo count ($sku);
// echo "<br>";
// echo count ($position);
// exit;
$resource = Mage::getSingleton('core/resource'); //get an instance of the core resource
 //get an instance of the write connection
$connection = $resource->getConnection('core_write');
$tableName = $resource->getTableName('catalog/category_product'); //this should add the prefix if you have on
foreach($sku as $skukey=>$skuname){


// print_r($position);exit;
//$_GET['sku']; //not really safe to read from $_GET but for test purposes it will do.
$id = Mage::getModel('catalog/product')->getIdBySku($skuname); //get the product id.

if(!$id)
continue;

  echo $sql = "UPDATE {$tableName} SET `position` = '".$position[$skukey]."' WHERE `product_id` = {$id}";

  //set the position to 0 for the product in all the categories.
 //run the query
$connection->query($sql);

}

/*

$product_sku = "tshirt-girls-sansani";
$product_id = Mage::getModel("catalog/product")->getIdBySku( $product_sku );
$product = Mage::getModel("catalog/product")->load( $product_id );
echo $product_name = $product->getName();echo '<br>';
echo $product_image = $product->getImageUrl();echo '<br>';
echo $product_code = $product->getDesignCode();
*/
/* $cat = Mage::getModel('catalog/category')->load(4);
$subcats = $cat->getChildren();
foreach(array_reverse(explode(',',$subcats)) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	
   echo $name."<br>";
  }
} */
/*

$menuStr = '';
$sessionCustomer =Mage::getSingleton("customer/session");
		if ($sessionCustomer->isLoggedIn()) {
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLoginUrl().'"><i class="mi mi-Login"></i>Login</a></li>';
		}	
			
			
		
		 else{ 
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLogoutUrl().'"><i class="mi mi-logout"></i>Logout</a></li>';
		 } 
/* $cat_id = 154;

$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true)->addAttributeToSort('position');


foreach ($collection as $cat) {
    echo $cat->getId().' '.'position'.  $cat->getPosition()."<br>";
} */

/* $cat_id = 154;
$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true);

foreach ($collection as $cat) {
    echo $cat->getId().' '.$cat->getPosition()."<br>";
} */
/*
$categoryId = 292;    
$products = Mage::getModel('catalog/category')->load($categoryId)
->getProductCollection()
->addAttributeToSelect('*')
->addAttributeToFilter(
    'status',
    array('eq' => Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
);
// echo "<pre>";
// print_r($products);
 foreach($products as $product){
	$productid =  $product->getId();
	 
	 $productt = Mage::getModel('catalog/product')->load($productid);
	 
	 //echo Mage::getModel('catalog/product_media_config')->getMediaUrl( $productt->getImage())."<br>";
	  echo $productt->getSku()."<br>";
	 
}
*/

 
 
 // echo "<pre>";           
 // print_r ($products);
  



// foreach ($collection as $product) {
    //get associated products (different sizes etc)
	//echo $product->getId();die;
    // $associatedProducts = Mage::getModel('catalog/product_type_configurable')->load($product->getId());

    // foreach ($associatedProducts as $simple) {
        //load product
        // echo $simple->getSku()."<br>";
		
		//$simple_product = Mage::getModel('catalog/product')->load($simple->getId());
		

        

    // }
// }


// foreach ($collection as $product){
   // $prod_id = $product->getSku();
    // echo $prod_id;
	// echo "<br>";
    
	




/*$ids = array("85726","85748","85749","85752","85756","85763","85767","85777","103274","103298","201740","85727","85739","85747","85750","85751","85757","85764","85768","85772","85778","85728","85758","85765","85769","85773","103275","201733","201734","85729","85738","85753","85759","85766","85774","103297","85730","85754","85760","85775","103276","201723","201735","85733","85737","85755","85761","85770","85776","85734","85762","85771","103277","201736","201739","85735","85741","201737","201738","85736","85740","85732","103278","85742","103270","85731","85743","85744","103271","85745","103272","85746","103273","103279","103280","103281","103282","103283","103284","103285","103286","103287","103288","103289","103290","103291","103292","103293","103294","103295","103296","201729","201730","201732","201745","201731");
*/


//print_r($prdIds);
/*foreach($prdIds as $product_id); {
 $obj = Mage::getModel('catalog/product');
 $_product = $obj->load($product_id);
 // get Product's name
 $productid = $_product->getId();
 
}

$loadpro=Mage::getModel('catalog/product')->load($productid);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($loadpro->getId());

foreach($mediaApiItems as $item){
$datatemp=$mediaApi->remove($loadpro->getId(), $item['file']);

}
$loadpro->save();*/
 // $catID = $current_category->getId(); //or any specific category id, e.g. 5
// $children = Mage::getModel('catalog/category')->getCategories($catID);
// foreach ($children as $category) {
       // echo $category->getId();
        // echo $category->getName();
       
// }
/*$categoryIds = Mage::getModel('catalog/product')->load(75); 
$productSku = array();
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	$prod_id = array(); 	
	foreach($collection as $product) 
	{
		//echo $prod_id = $product->getName();
		if(!in_array($product->getSku(),$productSku))
		$productSku[] = $product->getSku();
		//echo "<br>";
	}	
}
echo '<pre>';exit;
print_r($productSku);
exit;*/
/*$productId=85781;
$_product = Mage::getModel('catalog/product')->load($productId);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
try {
    $items = $mediaApi->items($_product->getId());
    foreach($items as $item) {
      
		$mediaApi->remove($_product->getId(), $item['file']);
	
    }
} catch (Exception $exception){
    var_dump($exception);
    die('Exception Thrown');
}*/


/*$productId=85779;
$product = Mage::getModel('catalog/product')->load($productId);
$fullImgPath = 'Add full path of the image that you want add';
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($product->getId());
$attributes = $product->getTypeInstance()->getSetAttributes();
$gallery = $attributes['media_gallery'];
if (count($mediaApiItems) > 0) {
    foreach($mediaApiItems as $item){
            
			$mediaApi->remove($product->getId(), $item['file']);
            //deleting image source
            if($gallery->getBackend()->getImage($product, $item['file'])) {
               $gallery->getBackend()->removeImage($product, $item['file']);
            }
    }
    $product->save();
}*/




 //before adding need to save product
// change mobile case model name
/*echo '<pre>';
$folder = 'E:\xampplite\htdocs\projects\Active\jazzy1\media\blank_images/';
//print_r($_SERVER);
$target = $folder.'mobile-case-name-change/';
$weeds = array('.', '..'); 
$directories = array_diff(scandir($target), $weeds);
$foldername = array_values($directories); 
//print_r($foldername);

$designername = array();
$lines = file($folder.'designer.csv');

foreach ($lines as $key => $value)
{
    $designername[$key] = ($value);
}
//print_r($designername);

$magento = array();
$lines = file($folder.'magento.csv');

foreach ($lines as $key => $value)
{
    $magento[$key] = ($value);
}
//print_r($magento);

for($i=0;$i<count($foldername);$i++)
{
	$name = trim($foldername[$i]);	
	for($j=0;$j<count($designername);$j++)
	{
		if(trim($designername[$j]) == $name)
		{	
			$new_name = trim($magento[$j]);
			echo $target.$name;
			echo '  =>  ';
			echo $target.$new_name;
			if($new_name != '')
			{
				$r = rename($target.$name, $target.$new_name);
			}
			if($r == 0)
			{
				$fp = fopen($folder."not_found.txt", "a");
				fwrite($fp, $new_name."\r\n");
			}
			echo ' <br> ';
		}
     }
    //echo $r;die;
}*/

// delete category from asus

// Mage::register("isSecureArea", 1);
// for($i=1980;$i<2914;$i++)
// {		
	// Mage::getModel("catalog/category")->load($i)->delete();
	// echo $i." delete";
	// echo '<br>';
// }

/*$cat_id = 34; // a category id that you can get from admin
//$category = Mage::getModel('catalog/category')->load($categoryId);

$category = Mage::getModel('catalog/category')->load($cat_id); //put your category id here
$child = $category->getChildren();
if($child != '')
{
	$children = explode(",", $child);
	for($i=0;$i<count($children);$i++)
	{
		$sub_cat = Mage::getModel('catalog/category')->load($children[$i]);
		//print_r($sub_cat);die;
		echo '<strong>'.$sub_cat->getName().'</strong>';
		echo '<br>';
		$sub_cat_child = $sub_cat->getChildren();
		if($sub_cat_child != '')
		{
			$sub_cat_children = explode(",", $sub_cat_child);
			for($j=0;$j<count($sub_cat_children);$j++)
			{
				$sub_sub_cat = Mage::getModel('catalog/category')->load($sub_cat_children[$j]);
				echo $sub_sub_cat->getName();
				echo '<br>';
			}
		}
	}
}*/

// custom option



//For case 
/*$categoryIds = array(34);		
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	//$prod_id = array(); 	
	foreach($collection as $product) 
	{
		$prod_id = $product->getId();
		$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
		$rowArray = $read->fetchRow($select); 
		$val_id = $rowArray['value_id'];
		if(isset($val_id) && $val_id != '')
		{
			$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area'))->where('value_id = '.$val_id);
			$select_val_row = $read->fetchRow($select_val); 
			$design_val = $select_val_row['design_area'];
			
				$write->beginTransaction();
				$fields = array();
				$fields['label'] = 'Front';
				$fields['position'] = 1;
				$fields['disabled'] = 0;
				$fields['color'] = 0;
				$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"7f9aUF5koApiVC31"}';
				$where = $write->quoteInto('value_id =?', $val_id);
				$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
				$write->commit();
			
		}
	}
	echo $categoryIds[$cat].' Complete';
	echo '<br>';
}*/



/*$prod_collection = Mage::getModel('catalog/category')->load(3);
$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
foreach($collection as $product)
{
	$pid = $product->getId();
	//echo $pid;die;
	$chk_option_qry = $read->query("SELECT `option_id` FROM `catalog_product_option` WHERE `product_id` = '".$pid."'");
	$chk_option_res = $chk_option_qry->fetch();
	if($chk_option_res['option_id'] == '')
	{
		//insert brand
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 1)");
		$last_boption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_boption_id."', 0, 'Brand')");	
			
		$brand_arr = array("Apple", "Samsung", "Google", "Htc", "Lg", "Motorola", "Sony", "Xiaomi", "Nokia", "Blackberry", "Asus", "Lenovo", "Oppo", "Gionee");
		for($i=0;$i<14;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_boption_id."', '', '".$i."')");
			$last_option_btype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_btype_id."', 0, '".$brand_arr[$i]."')");
		}
		
		// insert type
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 0)");
		$last_toption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_toption_id."', 0, 'Type')");	
				
		$type_arr = array("Mobile Case", "Mobile Skin");
		for($i=0;$i<2;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_toption_id."', '', '".$i."')");
			$last_option_ttype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_ttype_id."', 0, '".$type_arr[$i]."')");
		}
		
		// insert Model
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 2)");
		$last_moption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_moption_id."', 0, 'Model')");	
				
		$model_arr = array("iPhone 6","iPhone 5c","iPhone 4&4s","iPad Mini/Retina","iPad Air","iPad 4","iPhone 6 Plus","iPad 3","iPad 2","iPhone 5/5s","iPad","iPad Air 2","iPad Mini 2","iPod Touch 4","iPodTouch 5","Galaxy Grand Duos","Galaxy S Advanced","Galaxy S Duos","Galaxy S Duos 2 (S7582) & Trend Plus (S7580)","Galaxy S5","Galaxy S4 Mini","Galaxy S3","Galaxy Grand 2 G7106","Galaxy Grand I9080/I9082","Galaxy Mega 5.8","Galaxy Note 2 N7100","Galaxy Note 3","Galaxy Note 4","Galaxy S4","Galaxy S2","Galaxy S3 Mini","Galaxy S5 Mini","Galaxy Mega 6.3I9200","Galaxy Note 8.0","Galaxy Note 10.1","Galaxy Ace","Galaxy Ace Plus","Galaxy Ace 2","Galaxy Ace 3","Galaxy Ace 4","Galaxy Tab 2","Galaxy Tab3 7.0","Galaxy Tab3 8.0","GalaxyTab3 10.1","Galaxy Tab 7.0 Plus","Galaxy Tab 7.7","Galaxy Core","Galaxy Win","Samsung Trend 2 Duos","Galaxy Nexus","Galaxy Y","Galaxy Alpha","Galaxy Xcover 2","Lumia 620","Lumia 920","Lumia 520","BB Z10","BB Q10","BB 9900","Nexus 6","Nexus 5","Nexus 4","Nexus 7","HTC Desire 816","HTC One M7","HTC One M8","HTC One M8 Mini","HTC One S","HTC One X","HTC Sensation XL G21","Lg GoogleNexus 5","Lg Google Nexus 4","LG OPTIMUS G E975","LG OPTIMUS L5 II (E460)","LG G2","LG G3","LG L70","LG L90","Moto E","Moto G","Moto G2","Moto razr d1","Moto razr d3","Moto X","Moto X2","Moto Google Nexus 6","Xperia Sp","Xperia M2","Xperia Z","Xperia Z1","Xperia Z2","Xperia Z3","Xperia Z1 mini","XIAOM mi2","XIAOMI Redmi 1S","Google Nexus 7","Google Nexus 8","Google Nexus 9","Google Nexus 10");
		for($i=0;$i<84;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_moption_id."', '', '".$i."')");
			$last_option_mtype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_mtype_id."', 0, '".$model_arr[$i]."')");
		}
	}
	echo $pid." complete";
	echo '<br>';
	
}*/
?><?php 
ini_set('display_errors', 1);
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
//ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once('app/Mage.php');
umask(0);
Mage::app('');
Mage::setIsDeveloperMode(true);
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database
 // $siteBaseUrl = Mage::helper('function/more')->getBhishoomSiteBaseUrl();
 // echo $siteBaseUrl; exit;

// $date = Mage::getModel('core/date')->date('Y-M-d');
// $date = (explode('-',$date));
// $date =  $date[1].','.$date[2].' '.$date[0];   
// echo $date; exit;

$sku = array("#sexy-grp5","9-two-11-grp5","aaj-se-daaru-band-grp5","aaj-se-pakka-padenge-grp5","abey-grp5","achha-lagta-hai-grp5","after-party-grp5","angreji-na-jhade-grp5","apne-baap-ko-mat-sikha-grp5","are-you-a-camera-grp5","are-you-a-compass-grp5","are-you-a-keyword-case-grp5","are-you-a-pataka-case-grp5","Ashleel- Launda-case-grp5","Astronaut- Monkey-case-grp5","Astronaut- Panda-case-grp5","Astronaut- Pug-case-grp5","Awara -Hoon-case-grp5","Awesome- In -Bed-case-grp5","Awesome-case-grp5","Baba- Ji- Ki- Booti-case-grp5","Babu- Bhaiya-case-grp5","Baby- In- A- Carrier-case-grp5","Baby- Making -Machine-case-grp5","Bach- Ke- Rahiyo-case-grp5","Back -To -The- Gym-case-grp5","Baklol-case-grp5","Bakwas- Band -Kar-case-grp5","Bar- Wars-case-grp5","Bas- Kar- Pagli-case-grp5","Bawli- Booch- Se- Kay-case-grp5","Be -The- Change-case-grp5","Beech -Me- Na- Pad-case-grp5","Being -Sallu-case-grp5","Being -Salman-case-grp5","Being -Stupid-case-grp5","Better- On- Facebook-case-grp5","Bhains- ki -taang-case-grp5","Biker- boy-case-grp5","Black- Dragon-case-grp5","Black- Panda-case-grp5","blah-case-grp5","blink-if-you-want-me-case-grp5","bob-maar-le-case-grp5","boom-case-grp5","born-intelligent-case-grp5","borrow-a-kiss-case-grp5","can-you-help-me-case-grp5","chad-gayi-hai-case-grp5","cheeze-badi-hai-mast-case-grp5","chill-bro-case-grp5","chill-with-me-case-grp5","classic-retro-cars-case-grp5","cmyk-cars-case-grp5","colorful-star-wars-case-grp5","colourful-shades-case-grp5","cool-story-babe-case-grp5","cool-story-brother-case-grp5","crash-code-case-grp5","crush-roll-smoke-case-grp5","cute-bus-case-grp5","dabangg-vader-case-grp5","dancing-cats-case-grp5","dark-side-se-panga-case-grp5","delhi-ka-launda-case-grp5","delhi-ki-laundi-grp5","desi-case-grp5","dhakkan-case-grp5","disobey-case-grp5","do-ghoont-zindagi-ke-case-grp5","donate-blood-case-grp5","don't-be-negative-case-grp5","don't-challenge-bihari--case-grp5","don't-teach-daddy-case-grp5","drink-pee-repeat-case-grp5","dripping-strips-case-grp5","drive-me-case-grp5","ego-kaam-bigade-case-grp5","f.b.i.-case-grp5","f.k.u.-case-grp5","fck-off-case-grp5","fck-you-case-grp5","force-it-case-grp5","fried-ya..!!-case-grp5","furrari-case-grp5","gaali-kisne-di-case-grp5","genius-hoon-case-grp5","get-drunk-case-grp5","ghani-angrej-na-ban-case-grp5","ghanta-engineer-case-grp5","ghanta-kuch-hoga-case-grp5","ghanta-mba-case-grp5","gogo-ka-badla-grp5","gogo-ka-gussa-case-grp5","green-goa-case-grp5","h.t.m.l.-case-grp5","hai-la-case-grp5","handsome-chap-case-grp5","hanging-camera-case-grp5","hangover-t-shirt-case-grp5","hari-putter-case-grp5","hata-sawan-ki-ghata-case-grp5","hatbay-case-grp5","hat-budbak-case-grp5","ha-thor-aa-case-grp5","hello-awesome-case-grp5","hello-namaaste-case-grp5","hi-case-grp5","hope-case-grp5","how-are-you-doing-case-grp5","hulkat-case-grp5","humse-na-takrana-case-grp5","i-am-a-player-case-grp5","i-am-drunk-case-grp5","i-am-not-drunk-case-grp5","i-am-sober-case-grp5","i-do-case-grp5","i-kiss-better-then-i-cook-case-grp5","i-know-you-case-grp5","ilaka-tera-dhamaka-mera-case-grp5","i-look--better-in--towel-case-grp5","i-love-goa-case-grp5","i-love-my-boy-friend-case-grp5","i-love-my-girlfriend-case-grp5","i'm-a-supergirl-case-grp5","i'm-available-case-grp5","i'm-not-weird-case-grp5","i'm-that-boy-case-grp5","i'm-that-girl-case-grp5","i-need-space-case-grp5","i-piss-awesomeness-case-grp5","i-train-superheroes-case-grp5","i-train-your-trainer-case-grp5","ittu-sa-tha-case-grp5","jeep-love-case-grp5","jerk-case-grp5","jindagi-jhandwa-case-grp5","joint-antehem-case-grp5","joint-effort-case-grp5","joint-family-case-grp5","joint-venture-case-grp5","just-did-it-case-grp5","kadki-chal-rahi-hai-case-grp5","kaunwa-gola-ke-ho-case-grp5","key-tade-hai-case-grp5","khamosh-case-grp5","khattak-case-grp5","killin'-it-case-grp5","krrish-fourth-case-grp5","kunwara-case-grp5","kya-lega-case-grp5","kya-maal-hai-yaar-case-grp5","kya-samja-case-grp5","kya-totta-hai-yaar-case-grp5","kya-ukhad-lega-case-grp5","ladaku-chokre-case-grp5","ladaku-gang-case-grp5","lafanga-case-grp5","laundi-case-grp5","lega-kya-case-grp5","lets-clean-india-case-grp5","let's-hook-up-case-grp5","let's-make-babies-case-grp5","lip-service-case-grp5","lo-khada-ho-gaya-case-grp5","lol-case-grp5","maalgaadi-case-grp5","macho-case-grp5","mahi-7-case-grp5","maja-ni-life-case-grp5","make-in-india-case-grp5","make-it-neat-case-grp5","make-love-not-babies-case-grp5","maradona-10-case-grp5","mard-ki-moonch-case-grp5","mein-virgin-hoon-case-grp5","mumbai-ka-launda-case-grp5","mumbai-ki-laundi-case-grp5","muscles-loading-case-grp5","music-in-the-air-case-grp5","myself-majnu-case-grp5","naam-toh-suna-hoga-case-grp5","nalayak-case-grp5","namastey-bitches-case-grp5","namo-case-grp5","namokar-case-grp5","njr-11-case-grp5","no-girlfriend-no-tension-case-grp5","no-means-no-case-grp5","off-&-on-case-grp5","omcase-grp5","ooi-ma-case-grp5","orgasm-donor-case-grp5","oye-hoye-case-grp5","pagalguy-case-grp5","pagla-case-grp5","pagla-gaye-kya-case-grp5","panda-in-a-bag-case-grp5","panda-love-case-grp5","pangaa-na-lena-case-grp5","party-chick-case-grp5","pati-patni-aur-mobile-case-grp5","pen-is-strong-case-grp5","personality-dekh-case-grp5","ph.d-case-grp5","pie-pie-ka-hisaab-case-grp5","playboy-case-grp5","pop-boombox-case-grp5","pop-cassettes-case-grp5","pop-lips-case-grp5","pow-case-grp5","pretty-drunk-case-grp5","psycho-case-grp5","pug-in-a-bag-case-grp5","race-with-me-case-grp5","ram-pyari-ki-chai-case-grp5","rap-chick-case-grp5","red-sunglasses-case-grp5","respect-case-grp5","retro-classic-car-case-grp5","ride-me-on-case-grp5","ronaldinho-10-case-grp5","ronaldo-7-case-grp5","rooney-10-case-grp5","round-is-a-shape-case-grp5","run-case-grp5","sachin-10-case-grp5","sada-sexy-raho-case-grp5","saiko-case-grp5","sanskari-babuji-case-grp5","scan-me-case-grp5","selfie-case-grp5","sexsi-case-grp5","sexy-bitch-case-grp5","sexy-inside-case-grp5","sexy-launda-case-grp5","sexy-laundi-case-grp5","sexy-munda-case-grp5","shattak-case-grp5","she-is-your-bhabhi-case-grp5","shut-your-mouth-case-grp5","star-band-case-grp5","stay-away-case-grp5","stay-cool-case-grp5","stop-following-me-case-grp5","stop-staring-me-case-grp5","super-bahubali-case-grp5","super-besharam-case-grp5","super-chirkoot-case-grp5","super-haramkhor-case-grp5","super-mahapurush-case-grp5","super-moustache-case-grp5","super-nalayak-case-grp5","super-namo-case-grp5","swagger-case-grp5","tanki-hai-saala-case-grp5","tantrik-owl-case-grp5","teri-kehke-loonga-case-grp5","thand-rakh-case-grp5","tharki-case-grp5","tharki-chokro-case-grp5","that's-my-spot-case-grp5","three-sisters-case-grp5","torn-avengers-tee-case-grp5","torn-batman-tee-case-grp5","torn-captain-america-tee-case-grp5","torn-spiderman-tee-case-grp5","torn-superman-tee-case-grp5","tuxedo-case-grp5","u-suck-case-grp5","u-turn-me-on-case-grp5","very-strong-girl-case-grp5","virat-18-case-grp5","virgin-bhaijaan-case-grp5","vodka-case-grp5","vrroooom-case-grp5","war-time-case-grp5","watt-lag-gayi-case-grp5","wearing-a-mirror-case-grp5","wham-case-grp5","what's-cooking-case-grp5","what's-up-man-case-grp5","what-the-fuck-case-grp5","white-sunglasses-case-grp5","who-am-i-case-grp5","work-smart-case-grp5","wow-case-grp5","x-man-wolf-case-grp5","yolo-case-grp5","you-are-a-bomb-case-grp5","you-are-cute-case-grp5","you-are-so-sweet-case-grp5","you-just-pissed-me-off-case-grp5","your-workout-is-my-warmup-case-grp5","zindagi-jhand-ho-hayi-hai-case-grp5");

$position = array("1003","1026","1005","1008","1026","1012","1008","1004","1002","1021","1023","1013","1023","1005","1002","1016","1020","1009","1005","1017","1013","1026","1020","1013","1011","1020","1008","1022","1008","1016","1017","1005","1011","1016","1020","1007","1011","1013","1013","1021","1017","1017","1023","1001","1021","1008","1011","1011","1008","1013","1011","1016","1013","1020","1006","1004","1009","1016","1010","1006","1010","1006","1009","1004","1010","1016","1011","1020","1006","1026","1005","1023","1017","1011","1005","1013","1020","1028","1010","1013","1017","1023","1002","1021","1011","1002","1011","1011","1008","1007","1007","1021","1001","1018","1010","1015","1010","1016","1025","1021","1026","1015","1011","1020","1002","1023","1028","1017","1018","1017","1016","1007","1023","1007","1023","1006","1015","1004","1018","1007","1006","1004","1015","1015","1007","1008","1008","1026","1026","1003","1003","1004","1005","1020","1023","1001","1012","1010","1009","1008","1010","1012","1017","1010","1006","1021","1023","1005","1002","1012","1018","1010","1018","1010","1010","1010","1017","1007","1017","1007","1005","1020","1010","1012","1012","1015","1004","1003","1010","1012","1010","1021","1010","1017","1017","1004","1002","1007","1020","1018","1015","1011","1003","1005","1022","1026","1001","1005","1010","1023","1028","1006","1015","1023","1022","1008","1004","1015","1004","1007","1004","1020","1009","1023","1017","1010","1020","1009","1019","1017","1026","1007","1017","1017","1008","1008","1001","1012","1007","1020","1030","1013","1003","1017","1021","1004","1004","1003","1015","1012","1008","1012","1012","1012","1008","1004","1004","1026","1025","1017","1003","1003","1017","1004","1008","1017","1008","1015","1010","1017","1015","1017","1006","1007","1005","1021","1020","1015","1017","1015","1023","1012","1004","1020","1021","1015","1017","1015","1020","1013","1007","1018","1005","1002","1023","1024","1010","1018","1029","1025","1003","1008","1015","1017","1008","1018","1029","1008","1006","1013","1010","1026","1016","1020","1008");
// echo count ($sku);
// echo "<br>";
// echo count ($position);
// exit;
$resource = Mage::getSingleton('core/resource'); //get an instance of the core resource
 //get an instance of the write connection
$connection = $resource->getConnection('core_write');
$tableName = $resource->getTableName('catalog/category_product'); //this should add the prefix if you have on
foreach($sku as $skukey=>$skuname){


// print_r($position);exit;
//$_GET['sku']; //not really safe to read from $_GET but for test purposes it will do.
$id = Mage::getModel('catalog/product')->getIdBySku($skuname); //get the product id.

if(!$id)
continue;

  echo $sql = "UPDATE {$tableName} SET `position` = '".$position[$skukey]."' WHERE `product_id` = {$id}";

  //set the position to 0 for the product in all the categories.
 //run the query
$connection->query($sql);

}

/*

$product_sku = "tshirt-girls-sansani";
$product_id = Mage::getModel("catalog/product")->getIdBySku( $product_sku );
$product = Mage::getModel("catalog/product")->load( $product_id );
echo $product_name = $product->getName();echo '<br>';
echo $product_image = $product->getImageUrl();echo '<br>';
echo $product_code = $product->getDesignCode();
*/
/* $cat = Mage::getModel('catalog/category')->load(4);
$subcats = $cat->getChildren();
foreach(array_reverse(explode(',',$subcats)) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	
   echo $name."<br>";
  }
} */
/*

$menuStr = '';
$sessionCustomer =Mage::getSingleton("customer/session");
		if ($sessionCustomer->isLoggedIn()) {
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLoginUrl().'"><i class="mi mi-Login"></i>Login</a></li>';
		}	
			
			
		
		 else{ 
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLogoutUrl().'"><i class="mi mi-logout"></i>Logout</a></li>';
		 } 
/* $cat_id = 154;

$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true)->addAttributeToSort('position');


foreach ($collection as $cat) {
    echo $cat->getId().' '.'position'.  $cat->getPosition()."<br>";
} */

/* $cat_id = 154;
$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true);

foreach ($collection as $cat) {
    echo $cat->getId().' '.$cat->getPosition()."<br>";
} */
/*
$categoryId = 292;    
$products = Mage::getModel('catalog/category')->load($categoryId)
->getProductCollection()
->addAttributeToSelect('*')
->addAttributeToFilter(
    'status',
    array('eq' => Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
);
// echo "<pre>";
// print_r($products);
 foreach($products as $product){
	$productid =  $product->getId();
	 
	 $productt = Mage::getModel('catalog/product')->load($productid);
	 
	 //echo Mage::getModel('catalog/product_media_config')->getMediaUrl( $productt->getImage())."<br>";
	  echo $productt->getSku()."<br>";
	 
}
*/

 
 
 // echo "<pre>";           
 // print_r ($products);
  



// foreach ($collection as $product) {
    //get associated products (different sizes etc)
	//echo $product->getId();die;
    // $associatedProducts = Mage::getModel('catalog/product_type_configurable')->load($product->getId());

    // foreach ($associatedProducts as $simple) {
        //load product
        // echo $simple->getSku()."<br>";
		
		//$simple_product = Mage::getModel('catalog/product')->load($simple->getId());
		

        

    // }
// }


// foreach ($collection as $product){
   // $prod_id = $product->getSku();
    // echo $prod_id;
	// echo "<br>";
    
	




/*$ids = array("85726","85748","85749","85752","85756","85763","85767","85777","103274","103298","201740","85727","85739","85747","85750","85751","85757","85764","85768","85772","85778","85728","85758","85765","85769","85773","103275","201733","201734","85729","85738","85753","85759","85766","85774","103297","85730","85754","85760","85775","103276","201723","201735","85733","85737","85755","85761","85770","85776","85734","85762","85771","103277","201736","201739","85735","85741","201737","201738","85736","85740","85732","103278","85742","103270","85731","85743","85744","103271","85745","103272","85746","103273","103279","103280","103281","103282","103283","103284","103285","103286","103287","103288","103289","103290","103291","103292","103293","103294","103295","103296","201729","201730","201732","201745","201731");
*/


//print_r($prdIds);
/*foreach($prdIds as $product_id); {
 $obj = Mage::getModel('catalog/product');
 $_product = $obj->load($product_id);
 // get Product's name
 $productid = $_product->getId();
 
}

$loadpro=Mage::getModel('catalog/product')->load($productid);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($loadpro->getId());

foreach($mediaApiItems as $item){
$datatemp=$mediaApi->remove($loadpro->getId(), $item['file']);

}
$loadpro->save();*/
 // $catID = $current_category->getId(); //or any specific category id, e.g. 5
// $children = Mage::getModel('catalog/category')->getCategories($catID);
// foreach ($children as $category) {
       // echo $category->getId();
        // echo $category->getName();
       
// }
/*$categoryIds = Mage::getModel('catalog/product')->load(75); 
$productSku = array();
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	$prod_id = array(); 	
	foreach($collection as $product) 
	{
		//echo $prod_id = $product->getName();
		if(!in_array($product->getSku(),$productSku))
		$productSku[] = $product->getSku();
		//echo "<br>";
	}	
}
echo '<pre>';exit;
print_r($productSku);
exit;*/
/*$productId=85781;
$_product = Mage::getModel('catalog/product')->load($productId);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
try {
    $items = $mediaApi->items($_product->getId());
    foreach($items as $item) {
      
		$mediaApi->remove($_product->getId(), $item['file']);
	
    }
} catch (Exception $exception){
    var_dump($exception);
    die('Exception Thrown');
}*/


/*$productId=85779;
$product = Mage::getModel('catalog/product')->load($productId);
$fullImgPath = 'Add full path of the image that you want add';
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($product->getId());
$attributes = $product->getTypeInstance()->getSetAttributes();
$gallery = $attributes['media_gallery'];
if (count($mediaApiItems) > 0) {
    foreach($mediaApiItems as $item){
            
			$mediaApi->remove($product->getId(), $item['file']);
            //deleting image source
            if($gallery->getBackend()->getImage($product, $item['file'])) {
               $gallery->getBackend()->removeImage($product, $item['file']);
            }
    }
    $product->save();
}*/




 //before adding need to save product
// change mobile case model name
/*echo '<pre>';
$folder = 'E:\xampplite\htdocs\projects\Active\jazzy1\media\blank_images/';
//print_r($_SERVER);
$target = $folder.'mobile-case-name-change/';
$weeds = array('.', '..'); 
$directories = array_diff(scandir($target), $weeds);
$foldername = array_values($directories); 
//print_r($foldername);

$designername = array();
$lines = file($folder.'designer.csv');

foreach ($lines as $key => $value)
{
    $designername[$key] = ($value);
}
//print_r($designername);

$magento = array();
$lines = file($folder.'magento.csv');

foreach ($lines as $key => $value)
{
    $magento[$key] = ($value);
}
//print_r($magento);

for($i=0;$i<count($foldername);$i++)
{
	$name = trim($foldername[$i]);	
	for($j=0;$j<count($designername);$j++)
	{
		if(trim($designername[$j]) == $name)
		{	
			$new_name = trim($magento[$j]);
			echo $target.$name;
			echo '  =>  ';
			echo $target.$new_name;
			if($new_name != '')
			{
				$r = rename($target.$name, $target.$new_name);
			}
			if($r == 0)
			{
				$fp = fopen($folder."not_found.txt", "a");
				fwrite($fp, $new_name."\r\n");
			}
			echo ' <br> ';
		}
     }
    //echo $r;die;
}*/

// delete category from asus

// Mage::register("isSecureArea", 1);
// for($i=1980;$i<2914;$i++)
// {		
	// Mage::getModel("catalog/category")->load($i)->delete();
	// echo $i." delete";
	// echo '<br>';
// }

/*$cat_id = 34; // a category id that you can get from admin
//$category = Mage::getModel('catalog/category')->load($categoryId);

$category = Mage::getModel('catalog/category')->load($cat_id); //put your category id here
$child = $category->getChildren();
if($child != '')
{
	$children = explode(",", $child);
	for($i=0;$i<count($children);$i++)
	{
		$sub_cat = Mage::getModel('catalog/category')->load($children[$i]);
		//print_r($sub_cat);die;
		echo '<strong>'.$sub_cat->getName().'</strong>';
		echo '<br>';
		$sub_cat_child = $sub_cat->getChildren();
		if($sub_cat_child != '')
		{
			$sub_cat_children = explode(",", $sub_cat_child);
			for($j=0;$j<count($sub_cat_children);$j++)
			{
				$sub_sub_cat = Mage::getModel('catalog/category')->load($sub_cat_children[$j]);
				echo $sub_sub_cat->getName();
				echo '<br>';
			}
		}
	}
}*/

// custom option



//For case 
/*$categoryIds = array(34);		
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	//$prod_id = array(); 	
	foreach($collection as $product) 
	{
		$prod_id = $product->getId();
		$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
		$rowArray = $read->fetchRow($select); 
		$val_id = $rowArray['value_id'];
		if(isset($val_id) && $val_id != '')
		{
			$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area'))->where('value_id = '.$val_id);
			$select_val_row = $read->fetchRow($select_val); 
			$design_val = $select_val_row['design_area'];
			
				$write->beginTransaction();
				$fields = array();
				$fields['label'] = 'Front';
				$fields['position'] = 1;
				$fields['disabled'] = 0;
				$fields['color'] = 0;
				$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"7f9aUF5koApiVC31"}';
				$where = $write->quoteInto('value_id =?', $val_id);
				$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
				$write->commit();
			
		}
	}
	echo $categoryIds[$cat].' Complete';
	echo '<br>';
}*/



/*$prod_collection = Mage::getModel('catalog/category')->load(3);
$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
foreach($collection as $product)
{
	$pid = $product->getId();
	//echo $pid;die;
	$chk_option_qry = $read->query("SELECT `option_id` FROM `catalog_product_option` WHERE `product_id` = '".$pid."'");
	$chk_option_res = $chk_option_qry->fetch();
	if($chk_option_res['option_id'] == '')
	{
		//insert brand
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 1)");
		$last_boption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_boption_id."', 0, 'Brand')");	
			
		$brand_arr = array("Apple", "Samsung", "Google", "Htc", "Lg", "Motorola", "Sony", "Xiaomi", "Nokia", "Blackberry", "Asus", "Lenovo", "Oppo", "Gionee");
		for($i=0;$i<14;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_boption_id."', '', '".$i."')");
			$last_option_btype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_btype_id."', 0, '".$brand_arr[$i]."')");
		}
		
		// insert type
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 0)");
		$last_toption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_toption_id."', 0, 'Type')");	
				
		$type_arr = array("Mobile Case", "Mobile Skin");
		for($i=0;$i<2;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_toption_id."', '', '".$i."')");
			$last_option_ttype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_ttype_id."', 0, '".$type_arr[$i]."')");
		}
		
		// insert Model
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 2)");
		$last_moption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_moption_id."', 0, 'Model')");	
				
		$model_arr = array("iPhone 6","iPhone 5c","iPhone 4&4s","iPad Mini/Retina","iPad Air","iPad 4","iPhone 6 Plus","iPad 3","iPad 2","iPhone 5/5s","iPad","iPad Air 2","iPad Mini 2","iPod Touch 4","iPodTouch 5","Galaxy Grand Duos","Galaxy S Advanced","Galaxy S Duos","Galaxy S Duos 2 (S7582) & Trend Plus (S7580)","Galaxy S5","Galaxy S4 Mini","Galaxy S3","Galaxy Grand 2 G7106","Galaxy Grand I9080/I9082","Galaxy Mega 5.8","Galaxy Note 2 N7100","Galaxy Note 3","Galaxy Note 4","Galaxy S4","Galaxy S2","Galaxy S3 Mini","Galaxy S5 Mini","Galaxy Mega 6.3I9200","Galaxy Note 8.0","Galaxy Note 10.1","Galaxy Ace","Galaxy Ace Plus","Galaxy Ace 2","Galaxy Ace 3","Galaxy Ace 4","Galaxy Tab 2","Galaxy Tab3 7.0","Galaxy Tab3 8.0","GalaxyTab3 10.1","Galaxy Tab 7.0 Plus","Galaxy Tab 7.7","Galaxy Core","Galaxy Win","Samsung Trend 2 Duos","Galaxy Nexus","Galaxy Y","Galaxy Alpha","Galaxy Xcover 2","Lumia 620","Lumia 920","Lumia 520","BB Z10","BB Q10","BB 9900","Nexus 6","Nexus 5","Nexus 4","Nexus 7","HTC Desire 816","HTC One M7","HTC One M8","HTC One M8 Mini","HTC One S","HTC One X","HTC Sensation XL G21","Lg GoogleNexus 5","Lg Google Nexus 4","LG OPTIMUS G E975","LG OPTIMUS L5 II (E460)","LG G2","LG G3","LG L70","LG L90","Moto E","Moto G","Moto G2","Moto razr d1","Moto razr d3","Moto X","Moto X2","Moto Google Nexus 6","Xperia Sp","Xperia M2","Xperia Z","Xperia Z1","Xperia Z2","Xperia Z3","Xperia Z1 mini","XIAOM mi2","XIAOMI Redmi 1S","Google Nexus 7","Google Nexus 8","Google Nexus 9","Google Nexus 10");
		for($i=0;$i<84;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_moption_id."', '', '".$i."')");
			$last_option_mtype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_mtype_id."', 0, '".$model_arr[$i]."')");
		}
	}
	echo $pid." complete";
	echo '<br>';
	
}*/
?><?php 
ini_set('display_errors', 1);
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
//ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once('app/Mage.php');
umask(0);
Mage::app('');
Mage::setIsDeveloperMode(true);
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database
 // $siteBaseUrl = Mage::helper('function/more')->getBhishoomSiteBaseUrl();
 // echo $siteBaseUrl; exit;

// $date = Mage::getModel('core/date')->date('Y-M-d');
// $date = (explode('-',$date));
// $date =  $date[1].','.$date[2].' '.$date[0];   
// echo $date; exit;

$sku = array("#sexy-grp5","9-two-11-grp5","aaj-se-daaru-band-grp5","aaj-se-pakka-padenge-grp5","abey-grp5","achha-lagta-hai-grp5","after-party-grp5","angreji-na-jhade-grp5","apne-baap-ko-mat-sikha-grp5","are-you-a-camera-grp5","are-you-a-compass-grp5","are-you-a-keyword-case-grp5","are-you-a-pataka-case-grp5","Ashleel- Launda-case-grp5","Astronaut- Monkey-case-grp5","Astronaut- Panda-case-grp5","Astronaut- Pug-case-grp5","Awara -Hoon-case-grp5","Awesome- In -Bed-case-grp5","Awesome-case-grp5","Baba- Ji- Ki- Booti-case-grp5","Babu- Bhaiya-case-grp5","Baby- In- A- Carrier-case-grp5","Baby- Making -Machine-case-grp5","Bach- Ke- Rahiyo-case-grp5","Back -To -The- Gym-case-grp5","Baklol-case-grp5","Bakwas- Band -Kar-case-grp5","Bar- Wars-case-grp5","Bas- Kar- Pagli-case-grp5","Bawli- Booch- Se- Kay-case-grp5","Be -The- Change-case-grp5","Beech -Me- Na- Pad-case-grp5","Being -Sallu-case-grp5","Being -Salman-case-grp5","Being -Stupid-case-grp5","Better- On- Facebook-case-grp5","Bhains- ki -taang-case-grp5","Biker- boy-case-grp5","Black- Dragon-case-grp5","Black- Panda-case-grp5","blah-case-grp5","blink-if-you-want-me-case-grp5","bob-maar-le-case-grp5","boom-case-grp5","born-intelligent-case-grp5","borrow-a-kiss-case-grp5","can-you-help-me-case-grp5","chad-gayi-hai-case-grp5","cheeze-badi-hai-mast-case-grp5","chill-bro-case-grp5","chill-with-me-case-grp5","classic-retro-cars-case-grp5","cmyk-cars-case-grp5","colorful-star-wars-case-grp5","colourful-shades-case-grp5","cool-story-babe-case-grp5","cool-story-brother-case-grp5","crash-code-case-grp5","crush-roll-smoke-case-grp5","cute-bus-case-grp5","dabangg-vader-case-grp5","dancing-cats-case-grp5","dark-side-se-panga-case-grp5","delhi-ka-launda-case-grp5","delhi-ki-laundi-grp5","desi-case-grp5","dhakkan-case-grp5","disobey-case-grp5","do-ghoont-zindagi-ke-case-grp5","donate-blood-case-grp5","don't-be-negative-case-grp5","don't-challenge-bihari--case-grp5","don't-teach-daddy-case-grp5","drink-pee-repeat-case-grp5","dripping-strips-case-grp5","drive-me-case-grp5","ego-kaam-bigade-case-grp5","f.b.i.-case-grp5","f.k.u.-case-grp5","fck-off-case-grp5","fck-you-case-grp5","force-it-case-grp5","fried-ya..!!-case-grp5","furrari-case-grp5","gaali-kisne-di-case-grp5","genius-hoon-case-grp5","get-drunk-case-grp5","ghani-angrej-na-ban-case-grp5","ghanta-engineer-case-grp5","ghanta-kuch-hoga-case-grp5","ghanta-mba-case-grp5","gogo-ka-badla-grp5","gogo-ka-gussa-case-grp5","green-goa-case-grp5","h.t.m.l.-case-grp5","hai-la-case-grp5","handsome-chap-case-grp5","hanging-camera-case-grp5","hangover-t-shirt-case-grp5","hari-putter-case-grp5","hata-sawan-ki-ghata-case-grp5","hatbay-case-grp5","hat-budbak-case-grp5","ha-thor-aa-case-grp5","hello-awesome-case-grp5","hello-namaaste-case-grp5","hi-case-grp5","hope-case-grp5","how-are-you-doing-case-grp5","hulkat-case-grp5","humse-na-takrana-case-grp5","i-am-a-player-case-grp5","i-am-drunk-case-grp5","i-am-not-drunk-case-grp5","i-am-sober-case-grp5","i-do-case-grp5","i-kiss-better-then-i-cook-case-grp5","i-know-you-case-grp5","ilaka-tera-dhamaka-mera-case-grp5","i-look--better-in--towel-case-grp5","i-love-goa-case-grp5","i-love-my-boy-friend-case-grp5","i-love-my-girlfriend-case-grp5","i'm-a-supergirl-case-grp5","i'm-available-case-grp5","i'm-not-weird-case-grp5","i'm-that-boy-case-grp5","i'm-that-girl-case-grp5","i-need-space-case-grp5","i-piss-awesomeness-case-grp5","i-train-superheroes-case-grp5","i-train-your-trainer-case-grp5","ittu-sa-tha-case-grp5","jeep-love-case-grp5","jerk-case-grp5","jindagi-jhandwa-case-grp5","joint-antehem-case-grp5","joint-effort-case-grp5","joint-family-case-grp5","joint-venture-case-grp5","just-did-it-case-grp5","kadki-chal-rahi-hai-case-grp5","kaunwa-gola-ke-ho-case-grp5","key-tade-hai-case-grp5","khamosh-case-grp5","khattak-case-grp5","killin'-it-case-grp5","krrish-fourth-case-grp5","kunwara-case-grp5","kya-lega-case-grp5","kya-maal-hai-yaar-case-grp5","kya-samja-case-grp5","kya-totta-hai-yaar-case-grp5","kya-ukhad-lega-case-grp5","ladaku-chokre-case-grp5","ladaku-gang-case-grp5","lafanga-case-grp5","laundi-case-grp5","lega-kya-case-grp5","lets-clean-india-case-grp5","let's-hook-up-case-grp5","let's-make-babies-case-grp5","lip-service-case-grp5","lo-khada-ho-gaya-case-grp5","lol-case-grp5","maalgaadi-case-grp5","macho-case-grp5","mahi-7-case-grp5","maja-ni-life-case-grp5","make-in-india-case-grp5","make-it-neat-case-grp5","make-love-not-babies-case-grp5","maradona-10-case-grp5","mard-ki-moonch-case-grp5","mein-virgin-hoon-case-grp5","mumbai-ka-launda-case-grp5","mumbai-ki-laundi-case-grp5","muscles-loading-case-grp5","music-in-the-air-case-grp5","myself-majnu-case-grp5","naam-toh-suna-hoga-case-grp5","nalayak-case-grp5","namastey-bitches-case-grp5","namo-case-grp5","namokar-case-grp5","njr-11-case-grp5","no-girlfriend-no-tension-case-grp5","no-means-no-case-grp5","off-&-on-case-grp5","omcase-grp5","ooi-ma-case-grp5","orgasm-donor-case-grp5","oye-hoye-case-grp5","pagalguy-case-grp5","pagla-case-grp5","pagla-gaye-kya-case-grp5","panda-in-a-bag-case-grp5","panda-love-case-grp5","pangaa-na-lena-case-grp5","party-chick-case-grp5","pati-patni-aur-mobile-case-grp5","pen-is-strong-case-grp5","personality-dekh-case-grp5","ph.d-case-grp5","pie-pie-ka-hisaab-case-grp5","playboy-case-grp5","pop-boombox-case-grp5","pop-cassettes-case-grp5","pop-lips-case-grp5","pow-case-grp5","pretty-drunk-case-grp5","psycho-case-grp5","pug-in-a-bag-case-grp5","race-with-me-case-grp5","ram-pyari-ki-chai-case-grp5","rap-chick-case-grp5","red-sunglasses-case-grp5","respect-case-grp5","retro-classic-car-case-grp5","ride-me-on-case-grp5","ronaldinho-10-case-grp5","ronaldo-7-case-grp5","rooney-10-case-grp5","round-is-a-shape-case-grp5","run-case-grp5","sachin-10-case-grp5","sada-sexy-raho-case-grp5","saiko-case-grp5","sanskari-babuji-case-grp5","scan-me-case-grp5","selfie-case-grp5","sexsi-case-grp5","sexy-bitch-case-grp5","sexy-inside-case-grp5","sexy-launda-case-grp5","sexy-laundi-case-grp5","sexy-munda-case-grp5","shattak-case-grp5","she-is-your-bhabhi-case-grp5","shut-your-mouth-case-grp5","star-band-case-grp5","stay-away-case-grp5","stay-cool-case-grp5","stop-following-me-case-grp5","stop-staring-me-case-grp5","super-bahubali-case-grp5","super-besharam-case-grp5","super-chirkoot-case-grp5","super-haramkhor-case-grp5","super-mahapurush-case-grp5","super-moustache-case-grp5","super-nalayak-case-grp5","super-namo-case-grp5","swagger-case-grp5","tanki-hai-saala-case-grp5","tantrik-owl-case-grp5","teri-kehke-loonga-case-grp5","thand-rakh-case-grp5","tharki-case-grp5","tharki-chokro-case-grp5","that's-my-spot-case-grp5","three-sisters-case-grp5","torn-avengers-tee-case-grp5","torn-batman-tee-case-grp5","torn-captain-america-tee-case-grp5","torn-spiderman-tee-case-grp5","torn-superman-tee-case-grp5","tuxedo-case-grp5","u-suck-case-grp5","u-turn-me-on-case-grp5","very-strong-girl-case-grp5","virat-18-case-grp5","virgin-bhaijaan-case-grp5","vodka-case-grp5","vrroooom-case-grp5","war-time-case-grp5","watt-lag-gayi-case-grp5","wearing-a-mirror-case-grp5","wham-case-grp5","what's-cooking-case-grp5","what's-up-man-case-grp5","what-the-fuck-case-grp5","white-sunglasses-case-grp5","who-am-i-case-grp5","work-smart-case-grp5","wow-case-grp5","x-man-wolf-case-grp5","yolo-case-grp5","you-are-a-bomb-case-grp5","you-are-cute-case-grp5","you-are-so-sweet-case-grp5","you-just-pissed-me-off-case-grp5","your-workout-is-my-warmup-case-grp5","zindagi-jhand-ho-hayi-hai-case-grp5"

,"zindagi-jhand-ho-hayi-hai-case-grp13"
);

$position = array("1003","1026","1005","1008","1026","1012","1008","1004","1002","1021","1023","1013","1023","1005","1002","1016","1020","1009","1005","1017","1013","1026","1020","1013","1011","1020","1008","1022","1008","1016","1017","1005","1011","1016","1020","1007","1011","1013","1013","1021","1017","1017","1023","1001","1021","1008","1011","1011","1008","1013","1011","1016","1013","1020","1006","1004","1009","1016","1010","1006","1010","1006","1009","1004","1010","1016","1011","1020","1006","1026","1005","1023","1017","1011","1005","1013","1020","1028","1010","1013","1017","1023","1002","1021","1011","1002","1011","1011","1008","1007","1007","1021","1001","1018","1010","1015","1010","1016","1025","1021","1026","1015","1011","1020","1002","1023","1028","1017","1018","1017","1016","1007","1023","1007","1023","1006","1015","1004","1018","1007","1006","1004","1015","1015","1007","1008","1008","1026","1026","1003","1003","1004","1005","1020","1023","1001","1012","1010","1009","1008","1010","1012","1017","1010","1006","1021","1023","1005","1002","1012","1018","1010","1018","1010","1010","1010","1017","1007","1017","1007","1005","1020","1010","1012","1012","1015","1004","1003","1010","1012","1010","1021","1010","1017","1017","1004","1002","1007","1020","1018","1015","1011","1003","1005","1022","1026","1001","1005","1010","1023","1028","1006","1015","1023","1022","1008","1004","1015","1004","1007","1004","1020","1009","1023","1017","1010","1020","1009","1019","1017","1026","1007","1017","1017","1008","1008","1001","1012","1007","1020","1030","1013","1003","1017","1021","1004","1004","1003","1015","1012","1008","1012","1012","1012","1008","1004","1004","1026","1025","1017","1003","1003","1017","1004","1008","1017","1008","1015","1010","1017","1015","1017","1006","1007","1005","1021","1020","1015","1017","1015","1023","1012","1004","1020","1021","1015","1017","1015","1020","1013","1007","1018","1005","1002","1023","1024","1010","1018","1029","1025","1003","1008","1015","1017","1008","1018","1029","1008","1006","1013","1010","1026","1016","1020","1008"

,"1008"
);
// echo count ($sku);
// echo "<br>";
// echo count ($position);
// exit;
$resource = Mage::getSingleton('core/resource'); //get an instance of the core resource
 //get an instance of the write connection
$connection = $resource->getConnection('core_write');
$tableName = $resource->getTableName('catalog/category_product'); //this should add the prefix if you have on
foreach($sku as $skukey=>$skuname){


// print_r($position);exit;
//$_GET['sku']; //not really safe to read from $_GET but for test purposes it will do.
$id = Mage::getModel('catalog/product')->getIdBySku($skuname); //get the product id.

if(!$id)
continue;

  echo $sql = "UPDATE {$tableName} SET `position` = '".$position[$skukey]."' WHERE `product_id` = {$id}";

  //set the position to 0 for the product in all the categories.
 //run the query
$connection->query($sql);

}

/*

$product_sku = "tshirt-girls-sansani";
$product_id = Mage::getModel("catalog/product")->getIdBySku( $product_sku );
$product = Mage::getModel("catalog/product")->load( $product_id );
echo $product_name = $product->getName();echo '<br>';
echo $product_image = $product->getImageUrl();echo '<br>';
echo $product_code = $product->getDesignCode();
*/
/* $cat = Mage::getModel('catalog/category')->load(4);
$subcats = $cat->getChildren();
foreach(array_reverse(explode(',',$subcats)) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	
   echo $name."<br>";
  }
} */
/*

$menuStr = '';
$sessionCustomer =Mage::getSingleton("customer/session");
		if ($sessionCustomer->isLoggedIn()) {
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLoginUrl().'"><i class="mi mi-Login"></i>Login</a></li>';
		}	
			
			
		
		 else{ 
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLogoutUrl().'"><i class="mi mi-logout"></i>Logout</a></li>';
		 } 
/* $cat_id = 154;

$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true)->addAttributeToSort('position');


foreach ($collection as $cat) {
    echo $cat->getId().' '.'position'.  $cat->getPosition()."<br>";
} */

/* $cat_id = 154;
$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true);

foreach ($collection as $cat) {
    echo $cat->getId().' '.$cat->getPosition()."<br>";
} */
/*
$categoryId = 292;    
$products = Mage::getModel('catalog/category')->load($categoryId)
->getProductCollection()
->addAttributeToSelect('*')
->addAttributeToFilter(
    'status',
    array('eq' => Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
);
// echo "<pre>";
// print_r($products);
 foreach($products as $product){
	$productid =  $product->getId();
	 
	 $productt = Mage::getModel('catalog/product')->load($productid);
	 
	 //echo Mage::getModel('catalog/product_media_config')->getMediaUrl( $productt->getImage())."<br>";
	  echo $productt->getSku()."<br>";
	 
}
*/

 
 
 // echo "<pre>";           
 // print_r ($products);
  



// foreach ($collection as $product) {
    //get associated products (different sizes etc)
	//echo $product->getId();die;
    // $associatedProducts = Mage::getModel('catalog/product_type_configurable')->load($product->getId());

    // foreach ($associatedProducts as $simple) {
        //load product
        // echo $simple->getSku()."<br>";
		
		//$simple_product = Mage::getModel('catalog/product')->load($simple->getId());
		

        

    // }
// }


// foreach ($collection as $product){
   // $prod_id = $product->getSku();
    // echo $prod_id;
	// echo "<br>";
    
	




/*$ids = array("85726","85748","85749","85752","85756","85763","85767","85777","103274","103298","201740","85727","85739","85747","85750","85751","85757","85764","85768","85772","85778","85728","85758","85765","85769","85773","103275","201733","201734","85729","85738","85753","85759","85766","85774","103297","85730","85754","85760","85775","103276","201723","201735","85733","85737","85755","85761","85770","85776","85734","85762","85771","103277","201736","201739","85735","85741","201737","201738","85736","85740","85732","103278","85742","103270","85731","85743","85744","103271","85745","103272","85746","103273","103279","103280","103281","103282","103283","103284","103285","103286","103287","103288","103289","103290","103291","103292","103293","103294","103295","103296","201729","201730","201732","201745","201731");
*/


//print_r($prdIds);
/*foreach($prdIds as $product_id); {
 $obj = Mage::getModel('catalog/product');
 $_product = $obj->load($product_id);
 // get Product's name
 $productid = $_product->getId();
 
}

$loadpro=Mage::getModel('catalog/product')->load($productid);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($loadpro->getId());

foreach($mediaApiItems as $item){
$datatemp=$mediaApi->remove($loadpro->getId(), $item['file']);

}
$loadpro->save();*/
 // $catID = $current_category->getId(); //or any specific category id, e.g. 5
// $children = Mage::getModel('catalog/category')->getCategories($catID);
// foreach ($children as $category) {
       // echo $category->getId();
        // echo $category->getName();
       
// }
/*$categoryIds = Mage::getModel('catalog/product')->load(75); 
$productSku = array();
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	$prod_id = array(); 	
	foreach($collection as $product) 
	{
		//echo $prod_id = $product->getName();
		if(!in_array($product->getSku(),$productSku))
		$productSku[] = $product->getSku();
		//echo "<br>";
	}	
}
echo '<pre>';exit;
print_r($productSku);
exit;*/
/*$productId=85781;
$_product = Mage::getModel('catalog/product')->load($productId);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
try {
    $items = $mediaApi->items($_product->getId());
    foreach($items as $item) {
      
		$mediaApi->remove($_product->getId(), $item['file']);
	
    }
} catch (Exception $exception){
    var_dump($exception);
    die('Exception Thrown');
}*/


/*$productId=85779;
$product = Mage::getModel('catalog/product')->load($productId);
$fullImgPath = 'Add full path of the image that you want add';
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($product->getId());
$attributes = $product->getTypeInstance()->getSetAttributes();
$gallery = $attributes['media_gallery'];
if (count($mediaApiItems) > 0) {
    foreach($mediaApiItems as $item){
            
			$mediaApi->remove($product->getId(), $item['file']);
            //deleting image source
            if($gallery->getBackend()->getImage($product, $item['file'])) {
               $gallery->getBackend()->removeImage($product, $item['file']);
            }
    }
    $product->save();
}*/




 //before adding need to save product
// change mobile case model name
/*echo '<pre>';
$folder = 'E:\xampplite\htdocs\projects\Active\jazzy1\media\blank_images/';
//print_r($_SERVER);
$target = $folder.'mobile-case-name-change/';
$weeds = array('.', '..'); 
$directories = array_diff(scandir($target), $weeds);
$foldername = array_values($directories); 
//print_r($foldername);

$designername = array();
$lines = file($folder.'designer.csv');

foreach ($lines as $key => $value)
{
    $designername[$key] = ($value);
}
//print_r($designername);

$magento = array();
$lines = file($folder.'magento.csv');

foreach ($lines as $key => $value)
{
    $magento[$key] = ($value);
}
//print_r($magento);

for($i=0;$i<count($foldername);$i++)
{
	$name = trim($foldername[$i]);	
	for($j=0;$j<count($designername);$j++)
	{
		if(trim($designername[$j]) == $name)
		{	
			$new_name = trim($magento[$j]);
			echo $target.$name;
			echo '  =>  ';
			echo $target.$new_name;
			if($new_name != '')
			{
				$r = rename($target.$name, $target.$new_name);
			}
			if($r == 0)
			{
				$fp = fopen($folder."not_found.txt", "a");
				fwrite($fp, $new_name."\r\n");
			}
			echo ' <br> ';
		}
     }
    //echo $r;die;
}*/

// delete category from asus

// Mage::register("isSecureArea", 1);
// for($i=1980;$i<2914;$i++)
// {		
	// Mage::getModel("catalog/category")->load($i)->delete();
	// echo $i." delete";
	// echo '<br>';
// }

/*$cat_id = 34; // a category id that you can get from admin
//$category = Mage::getModel('catalog/category')->load($categoryId);

$category = Mage::getModel('catalog/category')->load($cat_id); //put your category id here
$child = $category->getChildren();
if($child != '')
{
	$children = explode(",", $child);
	for($i=0;$i<count($children);$i++)
	{
		$sub_cat = Mage::getModel('catalog/category')->load($children[$i]);
		//print_r($sub_cat);die;
		echo '<strong>'.$sub_cat->getName().'</strong>';
		echo '<br>';
		$sub_cat_child = $sub_cat->getChildren();
		if($sub_cat_child != '')
		{
			$sub_cat_children = explode(",", $sub_cat_child);
			for($j=0;$j<count($sub_cat_children);$j++)
			{
				$sub_sub_cat = Mage::getModel('catalog/category')->load($sub_cat_children[$j]);
				echo $sub_sub_cat->getName();
				echo '<br>';
			}
		}
	}
}*/

// custom option



//For case 
/*$categoryIds = array(34);		
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	//$prod_id = array(); 	
	foreach($collection as $product) 
	{
		$prod_id = $product->getId();
		$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
		$rowArray = $read->fetchRow($select); 
		$val_id = $rowArray['value_id'];
		if(isset($val_id) && $val_id != '')
		{
			$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area'))->where('value_id = '.$val_id);
			$select_val_row = $read->fetchRow($select_val); 
			$design_val = $select_val_row['design_area'];
			
				$write->beginTransaction();
				$fields = array();
				$fields['label'] = 'Front';
				$fields['position'] = 1;
				$fields['disabled'] = 0;
				$fields['color'] = 0;
				$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"7f9aUF5koApiVC31"}';
				$where = $write->quoteInto('value_id =?', $val_id);
				$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
				$write->commit();
			
		}
	}
	echo $categoryIds[$cat].' Complete';
	echo '<br>';
}*/



/*$prod_collection = Mage::getModel('catalog/category')->load(3);
$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
foreach($collection as $product)
{
	$pid = $product->getId();
	//echo $pid;die;
	$chk_option_qry = $read->query("SELECT `option_id` FROM `catalog_product_option` WHERE `product_id` = '".$pid."'");
	$chk_option_res = $chk_option_qry->fetch();
	if($chk_option_res['option_id'] == '')
	{
		//insert brand
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 1)");
		$last_boption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_boption_id."', 0, 'Brand')");	
			
		$brand_arr = array("Apple", "Samsung", "Google", "Htc", "Lg", "Motorola", "Sony", "Xiaomi", "Nokia", "Blackberry", "Asus", "Lenovo", "Oppo", "Gionee");
		for($i=0;$i<14;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_boption_id."', '', '".$i."')");
			$last_option_btype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_btype_id."', 0, '".$brand_arr[$i]."')");
		}
		
		// insert type
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 0)");
		$last_toption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_toption_id."', 0, 'Type')");	
				
		$type_arr = array("Mobile Case", "Mobile Skin");
		for($i=0;$i<2;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_toption_id."', '', '".$i."')");
			$last_option_ttype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_ttype_id."', 0, '".$type_arr[$i]."')");
		}
		
		// insert Model
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 2)");
		$last_moption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_moption_id."', 0, 'Model')");	
				
		$model_arr = array("iPhone 6","iPhone 5c","iPhone 4&4s","iPad Mini/Retina","iPad Air","iPad 4","iPhone 6 Plus","iPad 3","iPad 2","iPhone 5/5s","iPad","iPad Air 2","iPad Mini 2","iPod Touch 4","iPodTouch 5","Galaxy Grand Duos","Galaxy S Advanced","Galaxy S Duos","Galaxy S Duos 2 (S7582) & Trend Plus (S7580)","Galaxy S5","Galaxy S4 Mini","Galaxy S3","Galaxy Grand 2 G7106","Galaxy Grand I9080/I9082","Galaxy Mega 5.8","Galaxy Note 2 N7100","Galaxy Note 3","Galaxy Note 4","Galaxy S4","Galaxy S2","Galaxy S3 Mini","Galaxy S5 Mini","Galaxy Mega 6.3I9200","Galaxy Note 8.0","Galaxy Note 10.1","Galaxy Ace","Galaxy Ace Plus","Galaxy Ace 2","Galaxy Ace 3","Galaxy Ace 4","Galaxy Tab 2","Galaxy Tab3 7.0","Galaxy Tab3 8.0","GalaxyTab3 10.1","Galaxy Tab 7.0 Plus","Galaxy Tab 7.7","Galaxy Core","Galaxy Win","Samsung Trend 2 Duos","Galaxy Nexus","Galaxy Y","Galaxy Alpha","Galaxy Xcover 2","Lumia 620","Lumia 920","Lumia 520","BB Z10","BB Q10","BB 9900","Nexus 6","Nexus 5","Nexus 4","Nexus 7","HTC Desire 816","HTC One M7","HTC One M8","HTC One M8 Mini","HTC One S","HTC One X","HTC Sensation XL G21","Lg GoogleNexus 5","Lg Google Nexus 4","LG OPTIMUS G E975","LG OPTIMUS L5 II (E460)","LG G2","LG G3","LG L70","LG L90","Moto E","Moto G","Moto G2","Moto razr d1","Moto razr d3","Moto X","Moto X2","Moto Google Nexus 6","Xperia Sp","Xperia M2","Xperia Z","Xperia Z1","Xperia Z2","Xperia Z3","Xperia Z1 mini","XIAOM mi2","XIAOMI Redmi 1S","Google Nexus 7","Google Nexus 8","Google Nexus 9","Google Nexus 10");
		for($i=0;$i<84;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_moption_id."', '', '".$i."')");
			$last_option_mtype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_mtype_id."', 0, '".$model_arr[$i]."')");
		}
	}
	echo $pid." complete";
	echo '<br>';
	
}*/
?><?php 
ini_set('display_errors', 1);
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
//ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once('app/Mage.php');
umask(0);
Mage::app('');
Mage::setIsDeveloperMode(true);
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database
 // $siteBaseUrl = Mage::helper('function/more')->getBhishoomSiteBaseUrl();
 // echo $siteBaseUrl; exit;

// $date = Mage::getModel('core/date')->date('Y-M-d');
// $date = (explode('-',$date));
// $date =  $date[1].','.$date[2].' '.$date[0];   
// echo $date; exit;

$sku = array("#sexy-grp5","9-two-11-grp5","aaj-se-daaru-band-grp5","aaj-se-pakka-padenge-grp5","abey-grp5","achha-lagta-hai-grp5","after-party-grp5","angreji-na-jhade-grp5","apne-baap-ko-mat-sikha-grp5","are-you-a-camera-grp5","are-you-a-compass-grp5","are-you-a-keyword-case-grp5","are-you-a-pataka-case-grp5","Ashleel- Launda-case-grp5","Astronaut- Monkey-case-grp5","Astronaut- Panda-case-grp5","Astronaut- Pug-case-grp5","Awara -Hoon-case-grp5","Awesome- In -Bed-case-grp5","Awesome-case-grp5","Baba- Ji- Ki- Booti-case-grp5","Babu- Bhaiya-case-grp5","Baby- In- A- Carrier-case-grp5","Baby- Making -Machine-case-grp5","Bach- Ke- Rahiyo-case-grp5","Back -To -The- Gym-case-grp5","Baklol-case-grp5","Bakwas- Band -Kar-case-grp5","Bar- Wars-case-grp5","Bas- Kar- Pagli-case-grp5","Bawli- Booch- Se- Kay-case-grp5","Be -The- Change-case-grp5","Beech -Me- Na- Pad-case-grp5","Being -Sallu-case-grp5","Being -Salman-case-grp5","Being -Stupid-case-grp5","Better- On- Facebook-case-grp5","Bhains- ki -taang-case-grp5","Biker- boy-case-grp5","Black- Dragon-case-grp5","Black- Panda-case-grp5","blah-case-grp5","blink-if-you-want-me-case-grp5","bob-maar-le-case-grp5","boom-case-grp5","born-intelligent-case-grp5","borrow-a-kiss-case-grp5","can-you-help-me-case-grp5","chad-gayi-hai-case-grp5","cheeze-badi-hai-mast-case-grp5","chill-bro-case-grp5","chill-with-me-case-grp5","classic-retro-cars-case-grp5","cmyk-cars-case-grp5","colorful-star-wars-case-grp5","colourful-shades-case-grp5","cool-story-babe-case-grp5","cool-story-brother-case-grp5","crash-code-case-grp5","crush-roll-smoke-case-grp5","cute-bus-case-grp5","dabangg-vader-case-grp5","dancing-cats-case-grp5","dark-side-se-panga-case-grp5","delhi-ka-launda-case-grp5","delhi-ki-laundi-grp5","desi-case-grp5","dhakkan-case-grp5","disobey-case-grp5","do-ghoont-zindagi-ke-case-grp5","donate-blood-case-grp5","don't-be-negative-case-grp5","don't-challenge-bihari--case-grp5","don't-teach-daddy-case-grp5","drink-pee-repeat-case-grp5","dripping-strips-case-grp5","drive-me-case-grp5","ego-kaam-bigade-case-grp5","f.b.i.-case-grp5","f.k.u.-case-grp5","fck-off-case-grp5","fck-you-case-grp5","force-it-case-grp5","fried-ya..!!-case-grp5","furrari-case-grp5","gaali-kisne-di-case-grp5","genius-hoon-case-grp5","get-drunk-case-grp5","ghani-angrej-na-ban-case-grp5","ghanta-engineer-case-grp5","ghanta-kuch-hoga-case-grp5","ghanta-mba-case-grp5","gogo-ka-badla-grp5","gogo-ka-gussa-case-grp5","green-goa-case-grp5","h.t.m.l.-case-grp5","hai-la-case-grp5","handsome-chap-case-grp5","hanging-camera-case-grp5","hangover-t-shirt-case-grp5","hari-putter-case-grp5","hata-sawan-ki-ghata-case-grp5","hatbay-case-grp5","hat-budbak-case-grp5","ha-thor-aa-case-grp5","hello-awesome-case-grp5","hello-namaaste-case-grp5","hi-case-grp5","hope-case-grp5","how-are-you-doing-case-grp5","hulkat-case-grp5","humse-na-takrana-case-grp5","i-am-a-player-case-grp5","i-am-drunk-case-grp5","i-am-not-drunk-case-grp5","i-am-sober-case-grp5","i-do-case-grp5","i-kiss-better-then-i-cook-case-grp5","i-know-you-case-grp5","ilaka-tera-dhamaka-mera-case-grp5","i-look--better-in--towel-case-grp5","i-love-goa-case-grp5","i-love-my-boy-friend-case-grp5","i-love-my-girlfriend-case-grp5","i'm-a-supergirl-case-grp5","i'm-available-case-grp5","i'm-not-weird-case-grp5","i'm-that-boy-case-grp5","i'm-that-girl-case-grp5","i-need-space-case-grp5","i-piss-awesomeness-case-grp5","i-train-superheroes-case-grp5","i-train-your-trainer-case-grp5","ittu-sa-tha-case-grp5","jeep-love-case-grp5","jerk-case-grp5","jindagi-jhandwa-case-grp5","joint-antehem-case-grp5","joint-effort-case-grp5","joint-family-case-grp5","joint-venture-case-grp5","just-did-it-case-grp5","kadki-chal-rahi-hai-case-grp5","kaunwa-gola-ke-ho-case-grp5","key-tade-hai-case-grp5","khamosh-case-grp5","khattak-case-grp5","killin'-it-case-grp5","krrish-fourth-case-grp5","kunwara-case-grp5","kya-lega-case-grp5","kya-maal-hai-yaar-case-grp5","kya-samja-case-grp5","kya-totta-hai-yaar-case-grp5","kya-ukhad-lega-case-grp5","ladaku-chokre-case-grp5","ladaku-gang-case-grp5","lafanga-case-grp5","laundi-case-grp5","lega-kya-case-grp5","lets-clean-india-case-grp5","let's-hook-up-case-grp5","let's-make-babies-case-grp5","lip-service-case-grp5","lo-khada-ho-gaya-case-grp5","lol-case-grp5","maalgaadi-case-grp5","macho-case-grp5","mahi-7-case-grp5","maja-ni-life-case-grp5","make-in-india-case-grp5","make-it-neat-case-grp5","make-love-not-babies-case-grp5","maradona-10-case-grp5","mard-ki-moonch-case-grp5","mein-virgin-hoon-case-grp5","mumbai-ka-launda-case-grp5","mumbai-ki-laundi-case-grp5","muscles-loading-case-grp5","music-in-the-air-case-grp5","myself-majnu-case-grp5","naam-toh-suna-hoga-case-grp5","nalayak-case-grp5","namastey-bitches-case-grp5","namo-case-grp5","namokar-case-grp5","njr-11-case-grp5","no-girlfriend-no-tension-case-grp5","no-means-no-case-grp5","off-&-on-case-grp5","omcase-grp5","ooi-ma-case-grp5","orgasm-donor-case-grp5","oye-hoye-case-grp5","pagalguy-case-grp5","pagla-case-grp5","pagla-gaye-kya-case-grp5","panda-in-a-bag-case-grp5","panda-love-case-grp5","pangaa-na-lena-case-grp5","party-chick-case-grp5","pati-patni-aur-mobile-case-grp5","pen-is-strong-case-grp5","personality-dekh-case-grp5","ph.d-case-grp5","pie-pie-ka-hisaab-case-grp5","playboy-case-grp5","pop-boombox-case-grp5","pop-cassettes-case-grp5","pop-lips-case-grp5","pow-case-grp5","pretty-drunk-case-grp5","psycho-case-grp5","pug-in-a-bag-case-grp5","race-with-me-case-grp5","ram-pyari-ki-chai-case-grp5","rap-chick-case-grp5","red-sunglasses-case-grp5","respect-case-grp5","retro-classic-car-case-grp5","ride-me-on-case-grp5","ronaldinho-10-case-grp5","ronaldo-7-case-grp5","rooney-10-case-grp5","round-is-a-shape-case-grp5","run-case-grp5","sachin-10-case-grp5","sada-sexy-raho-case-grp5","saiko-case-grp5","sanskari-babuji-case-grp5","scan-me-case-grp5","selfie-case-grp5","sexsi-case-grp5","sexy-bitch-case-grp5","sexy-inside-case-grp5","sexy-launda-case-grp5","sexy-laundi-case-grp5","sexy-munda-case-grp5","shattak-case-grp5","she-is-your-bhabhi-case-grp5","shut-your-mouth-case-grp5","star-band-case-grp5","stay-away-case-grp5","stay-cool-case-grp5","stop-following-me-case-grp5","stop-staring-me-case-grp5","super-bahubali-case-grp5","super-besharam-case-grp5","super-chirkoot-case-grp5","super-haramkhor-case-grp5","super-mahapurush-case-grp5","super-moustache-case-grp5","super-nalayak-case-grp5","super-namo-case-grp5","swagger-case-grp5","tanki-hai-saala-case-grp5","tantrik-owl-case-grp5","teri-kehke-loonga-case-grp5","thand-rakh-case-grp5","tharki-case-grp5","tharki-chokro-case-grp5","that's-my-spot-case-grp5","three-sisters-case-grp5","torn-avengers-tee-case-grp5","torn-batman-tee-case-grp5","torn-captain-america-tee-case-grp5","torn-spiderman-tee-case-grp5","torn-superman-tee-case-grp5","tuxedo-case-grp5","u-suck-case-grp5","u-turn-me-on-case-grp5","very-strong-girl-case-grp5","virat-18-case-grp5","virgin-bhaijaan-case-grp5","vodka-case-grp5","vrroooom-case-grp5","war-time-case-grp5","watt-lag-gayi-case-grp5","wearing-a-mirror-case-grp5","wham-case-grp5","what's-cooking-case-grp5","what's-up-man-case-grp5","what-the-fuck-case-grp5","white-sunglasses-case-grp5","who-am-i-case-grp5","work-smart-case-grp5","wow-case-grp5","x-man-wolf-case-grp5","yolo-case-grp5","you-are-a-bomb-case-grp5","you-are-cute-case-grp5","you-are-so-sweet-case-grp5","you-just-pissed-me-off-case-grp5","your-workout-is-my-warmup-case-grp5","zindagi-jhand-ho-hayi-hai-case-grp5"
,"zindagi-jhand-ho-hayi-hai-case-grp13");

$position = array("1003","1026","1005","1008","1026","1012","1008","1004","1002","1021","1023","1013","1023","1005","1002","1016","1020","1009","1005","1017","1013","1026","1020","1013","1011","1020","1008","1022","1008","1016","1017","1005","1011","1016","1020","1007","1011","1013","1013","1021","1017","1017","1023","1001","1021","1008","1011","1011","1008","1013","1011","1016","1013","1020","1006","1004","1009","1016","1010","1006","1010","1006","1009","1004","1010","1016","1011","1020","1006","1026","1005","1023","1017","1011","1005","1013","1020","1028","1010","1013","1017","1023","1002","1021","1011","1002","1011","1011","1008","1007","1007","1021","1001","1018","1010","1015","1010","1016","1025","1021","1026","1015","1011","1020","1002","1023","1028","1017","1018","1017","1016","1007","1023","1007","1023","1006","1015","1004","1018","1007","1006","1004","1015","1015","1007","1008","1008","1026","1026","1003","1003","1004","1005","1020","1023","1001","1012","1010","1009","1008","1010","1012","1017","1010","1006","1021","1023","1005","1002","1012","1018","1010","1018","1010","1010","1010","1017","1007","1017","1007","1005","1020","1010","1012","1012","1015","1004","1003","1010","1012","1010","1021","1010","1017","1017","1004","1002","1007","1020","1018","1015","1011","1003","1005","1022","1026","1001","1005","1010","1023","1028","1006","1015","1023","1022","1008","1004","1015","1004","1007","1004","1020","1009","1023","1017","1010","1020","1009","1019","1017","1026","1007","1017","1017","1008","1008","1001","1012","1007","1020","1030","1013","1003","1017","1021","1004","1004","1003","1015","1012","1008","1012","1012","1012","1008","1004","1004","1026","1025","1017","1003","1003","1017","1004","1008","1017","1008","1015","1010","1017","1015","1017","1006","1007","1005","1021","1020","1015","1017","1015","1023","1012","1004","1020","1021","1015","1017","1015","1020","1013","1007","1018","1005","1002","1023","1024","1010","1018","1029","1025","1003","1008","1015","1017","1008","1018","1029","1008","1006","1013","1010","1026","1016","1020","1008","1008");
// echo count ($sku);
// echo "<br>";
// echo count ($position);
// exit;
$resource = Mage::getSingleton('core/resource'); //get an instance of the core resource
 //get an instance of the write connection
$connection = $resource->getConnection('core_write');
$tableName = $resource->getTableName('catalog/category_product'); //this should add the prefix if you have on
foreach($sku as $skukey=>$skuname){


// print_r($position);exit;
//$_GET['sku']; //not really safe to read from $_GET but for test purposes it will do.
$id = Mage::getModel('catalog/product')->getIdBySku($skuname); //get the product id.

if(!$id)
continue;

  echo $sql = "UPDATE {$tableName} SET `position` = '".$position[$skukey]."' WHERE `product_id` = {$id}";

  //set the position to 0 for the product in all the categories.
 //run the query
$connection->query($sql);

}

/*

$product_sku = "tshirt-girls-sansani";
$product_id = Mage::getModel("catalog/product")->getIdBySku( $product_sku );
$product = Mage::getModel("catalog/product")->load( $product_id );
echo $product_name = $product->getName();echo '<br>';
echo $product_image = $product->getImageUrl();echo '<br>';
echo $product_code = $product->getDesignCode();
*/
/* $cat = Mage::getModel('catalog/category')->load(4);
$subcats = $cat->getChildren();
foreach(array_reverse(explode(',',$subcats)) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	
   echo $name."<br>";
  }
} */
/*

$menuStr = '';
$sessionCustomer =Mage::getSingleton("customer/session");
		if ($sessionCustomer->isLoggedIn()) {
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLoginUrl().'"><i class="mi mi-Login"></i>Login</a></li>';
		}	
			
			
		
		 else{ 
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLogoutUrl().'"><i class="mi mi-logout"></i>Logout</a></li>';
		 } 
/* $cat_id = 154;

$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true)->addAttributeToSort('position');


foreach ($collection as $cat) {
    echo $cat->getId().' '.'position'.  $cat->getPosition()."<br>";
} */

/* $cat_id = 154;
$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true);

foreach ($collection as $cat) {
    echo $cat->getId().' '.$cat->getPosition()."<br>";
} */
/*
$categoryId = 292;    
$products = Mage::getModel('catalog/category')->load($categoryId)
->getProductCollection()
->addAttributeToSelect('*')
->addAttributeToFilter(
    'status',
    array('eq' => Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
);
// echo "<pre>";
// print_r($products);
 foreach($products as $product){
	$productid =  $product->getId();
	 
	 $productt = Mage::getModel('catalog/product')->load($productid);
	 
	 //echo Mage::getModel('catalog/product_media_config')->getMediaUrl( $productt->getImage())."<br>";
	  echo $productt->getSku()."<br>";
	 
}
*/

 
 
 // echo "<pre>";           
 // print_r ($products);
  



// foreach ($collection as $product) {
    //get associated products (different sizes etc)
	//echo $product->getId();die;
    // $associatedProducts = Mage::getModel('catalog/product_type_configurable')->load($product->getId());

    // foreach ($associatedProducts as $simple) {
        //load product
        // echo $simple->getSku()."<br>";
		
		//$simple_product = Mage::getModel('catalog/product')->load($simple->getId());
		

        

    // }
// }


// foreach ($collection as $product){
   // $prod_id = $product->getSku();
    // echo $prod_id;
	// echo "<br>";
    
	




/*$ids = array("85726","85748","85749","85752","85756","85763","85767","85777","103274","103298","201740","85727","85739","85747","85750","85751","85757","85764","85768","85772","85778","85728","85758","85765","85769","85773","103275","201733","201734","85729","85738","85753","85759","85766","85774","103297","85730","85754","85760","85775","103276","201723","201735","85733","85737","85755","85761","85770","85776","85734","85762","85771","103277","201736","201739","85735","85741","201737","201738","85736","85740","85732","103278","85742","103270","85731","85743","85744","103271","85745","103272","85746","103273","103279","103280","103281","103282","103283","103284","103285","103286","103287","103288","103289","103290","103291","103292","103293","103294","103295","103296","201729","201730","201732","201745","201731");
*/


//print_r($prdIds);
/*foreach($prdIds as $product_id); {
 $obj = Mage::getModel('catalog/product');
 $_product = $obj->load($product_id);
 // get Product's name
 $productid = $_product->getId();
 
}

$loadpro=Mage::getModel('catalog/product')->load($productid);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($loadpro->getId());

foreach($mediaApiItems as $item){
$datatemp=$mediaApi->remove($loadpro->getId(), $item['file']);

}
$loadpro->save();*/
 // $catID = $current_category->getId(); //or any specific category id, e.g. 5
// $children = Mage::getModel('catalog/category')->getCategories($catID);
// foreach ($children as $category) {
       // echo $category->getId();
        // echo $category->getName();
       
// }
/*$categoryIds = Mage::getModel('catalog/product')->load(75); 
$productSku = array();
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	$prod_id = array(); 	
	foreach($collection as $product) 
	{
		//echo $prod_id = $product->getName();
		if(!in_array($product->getSku(),$productSku))
		$productSku[] = $product->getSku();
		//echo "<br>";
	}	
}
echo '<pre>';exit;
print_r($productSku);
exit;*/
/*$productId=85781;
$_product = Mage::getModel('catalog/product')->load($productId);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
try {
    $items = $mediaApi->items($_product->getId());
    foreach($items as $item) {
      
		$mediaApi->remove($_product->getId(), $item['file']);
	
    }
} catch (Exception $exception){
    var_dump($exception);
    die('Exception Thrown');
}*/


/*$productId=85779;
$product = Mage::getModel('catalog/product')->load($productId);
$fullImgPath = 'Add full path of the image that you want add';
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($product->getId());
$attributes = $product->getTypeInstance()->getSetAttributes();
$gallery = $attributes['media_gallery'];
if (count($mediaApiItems) > 0) {
    foreach($mediaApiItems as $item){
            
			$mediaApi->remove($product->getId(), $item['file']);
            //deleting image source
            if($gallery->getBackend()->getImage($product, $item['file'])) {
               $gallery->getBackend()->removeImage($product, $item['file']);
            }
    }
    $product->save();
}*/




 //before adding need to save product
// change mobile case model name
/*echo '<pre>';
$folder = 'E:\xampplite\htdocs\projects\Active\jazzy1\media\blank_images/';
//print_r($_SERVER);
$target = $folder.'mobile-case-name-change/';
$weeds = array('.', '..'); 
$directories = array_diff(scandir($target), $weeds);
$foldername = array_values($directories); 
//print_r($foldername);

$designername = array();
$lines = file($folder.'designer.csv');

foreach ($lines as $key => $value)
{
    $designername[$key] = ($value);
}
//print_r($designername);

$magento = array();
$lines = file($folder.'magento.csv');

foreach ($lines as $key => $value)
{
    $magento[$key] = ($value);
}
//print_r($magento);

for($i=0;$i<count($foldername);$i++)
{
	$name = trim($foldername[$i]);	
	for($j=0;$j<count($designername);$j++)
	{
		if(trim($designername[$j]) == $name)
		{	
			$new_name = trim($magento[$j]);
			echo $target.$name;
			echo '  =>  ';
			echo $target.$new_name;
			if($new_name != '')
			{
				$r = rename($target.$name, $target.$new_name);
			}
			if($r == 0)
			{
				$fp = fopen($folder."not_found.txt", "a");
				fwrite($fp, $new_name."\r\n");
			}
			echo ' <br> ';
		}
     }
    //echo $r;die;
}*/

// delete category from asus

// Mage::register("isSecureArea", 1);
// for($i=1980;$i<2914;$i++)
// {		
	// Mage::getModel("catalog/category")->load($i)->delete();
	// echo $i." delete";
	// echo '<br>';
// }

/*$cat_id = 34; // a category id that you can get from admin
//$category = Mage::getModel('catalog/category')->load($categoryId);

$category = Mage::getModel('catalog/category')->load($cat_id); //put your category id here
$child = $category->getChildren();
if($child != '')
{
	$children = explode(",", $child);
	for($i=0;$i<count($children);$i++)
	{
		$sub_cat = Mage::getModel('catalog/category')->load($children[$i]);
		//print_r($sub_cat);die;
		echo '<strong>'.$sub_cat->getName().'</strong>';
		echo '<br>';
		$sub_cat_child = $sub_cat->getChildren();
		if($sub_cat_child != '')
		{
			$sub_cat_children = explode(",", $sub_cat_child);
			for($j=0;$j<count($sub_cat_children);$j++)
			{
				$sub_sub_cat = Mage::getModel('catalog/category')->load($sub_cat_children[$j]);
				echo $sub_sub_cat->getName();
				echo '<br>';
			}
		}
	}
}*/

// custom option



//For case 
/*$categoryIds = array(34);		
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	//$prod_id = array(); 	
	foreach($collection as $product) 
	{
		$prod_id = $product->getId();
		$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
		$rowArray = $read->fetchRow($select); 
		$val_id = $rowArray['value_id'];
		if(isset($val_id) && $val_id != '')
		{
			$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area'))->where('value_id = '.$val_id);
			$select_val_row = $read->fetchRow($select_val); 
			$design_val = $select_val_row['design_area'];
			
				$write->beginTransaction();
				$fields = array();
				$fields['label'] = 'Front';
				$fields['position'] = 1;
				$fields['disabled'] = 0;
				$fields['color'] = 0;
				$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"7f9aUF5koApiVC31"}';
				$where = $write->quoteInto('value_id =?', $val_id);
				$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
				$write->commit();
			
		}
	}
	echo $categoryIds[$cat].' Complete';
	echo '<br>';
}*/



/*$prod_collection = Mage::getModel('catalog/category')->load(3);
$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
foreach($collection as $product)
{
	$pid = $product->getId();
	//echo $pid;die;
	$chk_option_qry = $read->query("SELECT `option_id` FROM `catalog_product_option` WHERE `product_id` = '".$pid."'");
	$chk_option_res = $chk_option_qry->fetch();
	if($chk_option_res['option_id'] == '')
	{
		//insert brand
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 1)");
		$last_boption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_boption_id."', 0, 'Brand')");	
			
		$brand_arr = array("Apple", "Samsung", "Google", "Htc", "Lg", "Motorola", "Sony", "Xiaomi", "Nokia", "Blackberry", "Asus", "Lenovo", "Oppo", "Gionee");
		for($i=0;$i<14;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_boption_id."', '', '".$i."')");
			$last_option_btype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_btype_id."', 0, '".$brand_arr[$i]."')");
		}
		
		// insert type
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 0)");
		$last_toption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_toption_id."', 0, 'Type')");	
				
		$type_arr = array("Mobile Case", "Mobile Skin");
		for($i=0;$i<2;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_toption_id."', '', '".$i."')");
			$last_option_ttype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_ttype_id."', 0, '".$type_arr[$i]."')");
		}
		
		// insert Model
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 2)");
		$last_moption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_moption_id."', 0, 'Model')");	
				
		$model_arr = array("iPhone 6","iPhone 5c","iPhone 4&4s","iPad Mini/Retina","iPad Air","iPad 4","iPhone 6 Plus","iPad 3","iPad 2","iPhone 5/5s","iPad","iPad Air 2","iPad Mini 2","iPod Touch 4","iPodTouch 5","Galaxy Grand Duos","Galaxy S Advanced","Galaxy S Duos","Galaxy S Duos 2 (S7582) & Trend Plus (S7580)","Galaxy S5","Galaxy S4 Mini","Galaxy S3","Galaxy Grand 2 G7106","Galaxy Grand I9080/I9082","Galaxy Mega 5.8","Galaxy Note 2 N7100","Galaxy Note 3","Galaxy Note 4","Galaxy S4","Galaxy S2","Galaxy S3 Mini","Galaxy S5 Mini","Galaxy Mega 6.3I9200","Galaxy Note 8.0","Galaxy Note 10.1","Galaxy Ace","Galaxy Ace Plus","Galaxy Ace 2","Galaxy Ace 3","Galaxy Ace 4","Galaxy Tab 2","Galaxy Tab3 7.0","Galaxy Tab3 8.0","GalaxyTab3 10.1","Galaxy Tab 7.0 Plus","Galaxy Tab 7.7","Galaxy Core","Galaxy Win","Samsung Trend 2 Duos","Galaxy Nexus","Galaxy Y","Galaxy Alpha","Galaxy Xcover 2","Lumia 620","Lumia 920","Lumia 520","BB Z10","BB Q10","BB 9900","Nexus 6","Nexus 5","Nexus 4","Nexus 7","HTC Desire 816","HTC One M7","HTC One M8","HTC One M8 Mini","HTC One S","HTC One X","HTC Sensation XL G21","Lg GoogleNexus 5","Lg Google Nexus 4","LG OPTIMUS G E975","LG OPTIMUS L5 II (E460)","LG G2","LG G3","LG L70","LG L90","Moto E","Moto G","Moto G2","Moto razr d1","Moto razr d3","Moto X","Moto X2","Moto Google Nexus 6","Xperia Sp","Xperia M2","Xperia Z","Xperia Z1","Xperia Z2","Xperia Z3","Xperia Z1 mini","XIAOM mi2","XIAOMI Redmi 1S","Google Nexus 7","Google Nexus 8","Google Nexus 9","Google Nexus 10");
		for($i=0;$i<84;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_moption_id."', '', '".$i."')");
			$last_option_mtype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_mtype_id."', 0, '".$model_arr[$i]."')");
		}
	}
	echo $pid." complete";
	echo '<br>';
	
}*/
?><?php 
ini_set('display_errors', 1);
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
//ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once('app/Mage.php');
umask(0);
Mage::app('');
Mage::setIsDeveloperMode(true);
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database
 // $siteBaseUrl = Mage::helper('function/more')->getBhishoomSiteBaseUrl();
 // echo $siteBaseUrl; exit;

// $date = Mage::getModel('core/date')->date('Y-M-d');
// $date = (explode('-',$date));
// $date =  $date[1].','.$date[2].' '.$date[0];   
// echo $date; exit;

$sku = array("#sexy-grp5","9-two-11-grp5","aaj-se-daaru-band-grp5","aaj-se-pakka-padenge-grp5","abey-grp5","achha-lagta-hai-grp5","after-party-grp5","angreji-na-jhade-grp5","apne-baap-ko-mat-sikha-grp5","are-you-a-camera-grp5","are-you-a-compass-grp5","are-you-a-keyword-case-grp5","are-you-a-pataka-case-grp5","Ashleel- Launda-case-grp5","Astronaut- Monkey-case-grp5","Astronaut- Panda-case-grp5","Astronaut- Pug-case-grp5","Awara -Hoon-case-grp5","Awesome- In -Bed-case-grp5","Awesome-case-grp5","Baba- Ji- Ki- Booti-case-grp5","Babu- Bhaiya-case-grp5","Baby- In- A- Carrier-case-grp5","Baby- Making -Machine-case-grp5","Bach- Ke- Rahiyo-case-grp5","Back -To -The- Gym-case-grp5","Baklol-case-grp5","Bakwas- Band -Kar-case-grp5","Bar- Wars-case-grp5","Bas- Kar- Pagli-case-grp5","Bawli- Booch- Se- Kay-case-grp5","Be -The- Change-case-grp5","Beech -Me- Na- Pad-case-grp5","Being -Sallu-case-grp5","Being -Salman-case-grp5","Being -Stupid-case-grp5","Better- On- Facebook-case-grp5","Bhains- ki -taang-case-grp5","Biker- boy-case-grp5","Black- Dragon-case-grp5","Black- Panda-case-grp5","blah-case-grp5","blink-if-you-want-me-case-grp5","bob-maar-le-case-grp5","boom-case-grp5","born-intelligent-case-grp5","borrow-a-kiss-case-grp5","can-you-help-me-case-grp5","chad-gayi-hai-case-grp5","cheeze-badi-hai-mast-case-grp5","chill-bro-case-grp5","chill-with-me-case-grp5","classic-retro-cars-case-grp5","cmyk-cars-case-grp5","colorful-star-wars-case-grp5","colourful-shades-case-grp5","cool-story-babe-case-grp5","cool-story-brother-case-grp5","crash-code-case-grp5","crush-roll-smoke-case-grp5","cute-bus-case-grp5","dabangg-vader-case-grp5","dancing-cats-case-grp5","dark-side-se-panga-case-grp5","delhi-ka-launda-case-grp5","delhi-ki-laundi-grp5","desi-case-grp5","dhakkan-case-grp5","disobey-case-grp5","do-ghoont-zindagi-ke-case-grp5","donate-blood-case-grp5","don't-be-negative-case-grp5","don't-challenge-bihari--case-grp5","don't-teach-daddy-case-grp5","drink-pee-repeat-case-grp5","dripping-strips-case-grp5","drive-me-case-grp5","ego-kaam-bigade-case-grp5","f.b.i.-case-grp5","f.k.u.-case-grp5","fck-off-case-grp5","fck-you-case-grp5","force-it-case-grp5","fried-ya..!!-case-grp5","furrari-case-grp5","gaali-kisne-di-case-grp5","genius-hoon-case-grp5","get-drunk-case-grp5","ghani-angrej-na-ban-case-grp5","ghanta-engineer-case-grp5","ghanta-kuch-hoga-case-grp5","ghanta-mba-case-grp5","gogo-ka-badla-grp5","gogo-ka-gussa-case-grp5","green-goa-case-grp5","h.t.m.l.-case-grp5","hai-la-case-grp5","handsome-chap-case-grp5","hanging-camera-case-grp5","hangover-t-shirt-case-grp5","hari-putter-case-grp5","hata-sawan-ki-ghata-case-grp5","hatbay-case-grp5","hat-budbak-case-grp5","ha-thor-aa-case-grp5","hello-awesome-case-grp5","hello-namaaste-case-grp5","hi-case-grp5","hope-case-grp5","how-are-you-doing-case-grp5","hulkat-case-grp5","humse-na-takrana-case-grp5","i-am-a-player-case-grp5","i-am-drunk-case-grp5","i-am-not-drunk-case-grp5","i-am-sober-case-grp5","i-do-case-grp5","i-kiss-better-then-i-cook-case-grp5","i-know-you-case-grp5","ilaka-tera-dhamaka-mera-case-grp5","i-look--better-in--towel-case-grp5","i-love-goa-case-grp5","i-love-my-boy-friend-case-grp5","i-love-my-girlfriend-case-grp5","i'm-a-supergirl-case-grp5","i'm-available-case-grp5","i'm-not-weird-case-grp5","i'm-that-boy-case-grp5","i'm-that-girl-case-grp5","i-need-space-case-grp5","i-piss-awesomeness-case-grp5","i-train-superheroes-case-grp5","i-train-your-trainer-case-grp5","ittu-sa-tha-case-grp5","jeep-love-case-grp5","jerk-case-grp5","jindagi-jhandwa-case-grp5","joint-antehem-case-grp5","joint-effort-case-grp5","joint-family-case-grp5","joint-venture-case-grp5","just-did-it-case-grp5","kadki-chal-rahi-hai-case-grp5","kaunwa-gola-ke-ho-case-grp5","key-tade-hai-case-grp5","khamosh-case-grp5","khattak-case-grp5","killin'-it-case-grp5","krrish-fourth-case-grp5","kunwara-case-grp5","kya-lega-case-grp5","kya-maal-hai-yaar-case-grp5","kya-samja-case-grp5","kya-totta-hai-yaar-case-grp5","kya-ukhad-lega-case-grp5","ladaku-chokre-case-grp5","ladaku-gang-case-grp5","lafanga-case-grp5","laundi-case-grp5","lega-kya-case-grp5","lets-clean-india-case-grp5","let's-hook-up-case-grp5","let's-make-babies-case-grp5","lip-service-case-grp5","lo-khada-ho-gaya-case-grp5","lol-case-grp5","maalgaadi-case-grp5","macho-case-grp5","mahi-7-case-grp5","maja-ni-life-case-grp5","make-in-india-case-grp5","make-it-neat-case-grp5","make-love-not-babies-case-grp5","maradona-10-case-grp5","mard-ki-moonch-case-grp5","mein-virgin-hoon-case-grp5","mumbai-ka-launda-case-grp5","mumbai-ki-laundi-case-grp5","muscles-loading-case-grp5","music-in-the-air-case-grp5","myself-majnu-case-grp5","naam-toh-suna-hoga-case-grp5","nalayak-case-grp5","namastey-bitches-case-grp5","namo-case-grp5","namokar-case-grp5","njr-11-case-grp5","no-girlfriend-no-tension-case-grp5","no-means-no-case-grp5","off-&-on-case-grp5","omcase-grp5","ooi-ma-case-grp5","orgasm-donor-case-grp5","oye-hoye-case-grp5","pagalguy-case-grp5","pagla-case-grp5","pagla-gaye-kya-case-grp5","panda-in-a-bag-case-grp5","panda-love-case-grp5","pangaa-na-lena-case-grp5","party-chick-case-grp5","pati-patni-aur-mobile-case-grp5","pen-is-strong-case-grp5","personality-dekh-case-grp5","ph.d-case-grp5","pie-pie-ka-hisaab-case-grp5","playboy-case-grp5","pop-boombox-case-grp5","pop-cassettes-case-grp5","pop-lips-case-grp5","pow-case-grp5","pretty-drunk-case-grp5","psycho-case-grp5","pug-in-a-bag-case-grp5","race-with-me-case-grp5","ram-pyari-ki-chai-case-grp5","rap-chick-case-grp5","red-sunglasses-case-grp5","respect-case-grp5","retro-classic-car-case-grp5","ride-me-on-case-grp5","ronaldinho-10-case-grp5","ronaldo-7-case-grp5","rooney-10-case-grp5","round-is-a-shape-case-grp5","run-case-grp5","sachin-10-case-grp5","sada-sexy-raho-case-grp5","saiko-case-grp5","sanskari-babuji-case-grp5","scan-me-case-grp5","selfie-case-grp5","sexsi-case-grp5","sexy-bitch-case-grp5","sexy-inside-case-grp5","sexy-launda-case-grp5","sexy-laundi-case-grp5","sexy-munda-case-grp5","shattak-case-grp5","she-is-your-bhabhi-case-grp5","shut-your-mouth-case-grp5","star-band-case-grp5","stay-away-case-grp5","stay-cool-case-grp5","stop-following-me-case-grp5","stop-staring-me-case-grp5","super-bahubali-case-grp5","super-besharam-case-grp5","super-chirkoot-case-grp5","super-haramkhor-case-grp5","super-mahapurush-case-grp5","super-moustache-case-grp5","super-nalayak-case-grp5","super-namo-case-grp5","swagger-case-grp5","tanki-hai-saala-case-grp5","tantrik-owl-case-grp5","teri-kehke-loonga-case-grp5","thand-rakh-case-grp5","tharki-case-grp5","tharki-chokro-case-grp5","that's-my-spot-case-grp5","three-sisters-case-grp5","torn-avengers-tee-case-grp5","torn-batman-tee-case-grp5","torn-captain-america-tee-case-grp5","torn-spiderman-tee-case-grp5","torn-superman-tee-case-grp5","tuxedo-case-grp5","u-suck-case-grp5","u-turn-me-on-case-grp5","very-strong-girl-case-grp5","virat-18-case-grp5","virgin-bhaijaan-case-grp5","vodka-case-grp5","vrroooom-case-grp5","war-time-case-grp5","watt-lag-gayi-case-grp5","wearing-a-mirror-case-grp5","wham-case-grp5","what's-cooking-case-grp5","what's-up-man-case-grp5","what-the-fuck-case-grp5","white-sunglasses-case-grp5","who-am-i-case-grp5","work-smart-case-grp5","wow-case-grp5","x-man-wolf-case-grp5","yolo-case-grp5","you-are-a-bomb-case-grp5","you-are-cute-case-grp5","you-are-so-sweet-case-grp5","you-just-pissed-me-off-case-grp5","your-workout-is-my-warmup-case-grp5","zindagi-jhand-ho-hayi-hai-case-grp5");

$position = array("1003","1026","1005","1008","1026","1012","1008","1004","1002","1021","1023","1013","1023","1005","1002","1016","1020","1009","1005","1017","1013","1026","1020","1013","1011","1020","1008","1022","1008","1016","1017","1005","1011","1016","1020","1007","1011","1013","1013","1021","1017","1017","1023","1001","1021","1008","1011","1011","1008","1013","1011","1016","1013","1020","1006","1004","1009","1016","1010","1006","1010","1006","1009","1004","1010","1016","1011","1020","1006","1026","1005","1023","1017","1011","1005","1013","1020","1028","1010","1013","1017","1023","1002","1021","1011","1002","1011","1011","1008","1007","1007","1021","1001","1018","1010","1015","1010","1016","1025","1021","1026","1015","1011","1020","1002","1023","1028","1017","1018","1017","1016","1007","1023","1007","1023","1006","1015","1004","1018","1007","1006","1004","1015","1015","1007","1008","1008","1026","1026","1003","1003","1004","1005","1020","1023","1001","1012","1010","1009","1008","1010","1012","1017","1010","1006","1021","1023","1005","1002","1012","1018","1010","1018","1010","1010","1010","1017","1007","1017","1007","1005","1020","1010","1012","1012","1015","1004","1003","1010","1012","1010","1021","1010","1017","1017","1004","1002","1007","1020","1018","1015","1011","1003","1005","1022","1026","1001","1005","1010","1023","1028","1006","1015","1023","1022","1008","1004","1015","1004","1007","1004","1020","1009","1023","1017","1010","1020","1009","1019","1017","1026","1007","1017","1017","1008","1008","1001","1012","1007","1020","1030","1013","1003","1017","1021","1004","1004","1003","1015","1012","1008","1012","1012","1012","1008","1004","1004","1026","1025","1017","1003","1003","1017","1004","1008","1017","1008","1015","1010","1017","1015","1017","1006","1007","1005","1021","1020","1015","1017","1015","1023","1012","1004","1020","1021","1015","1017","1015","1020","1013","1007","1018","1005","1002","1023","1024","1010","1018","1029","1025","1003","1008","1015","1017","1008","1018","1029","1008","1006","1013","1010","1026","1016","1020","1008");
// echo count ($sku);
// echo "<br>";
// echo count ($position);
// exit;
$resource = Mage::getSingleton('core/resource'); //get an instance of the core resource
 //get an instance of the write connection
$connection = $resource->getConnection('core_write');
$tableName = $resource->getTableName('catalog/category_product'); //this should add the prefix if you have on
foreach($sku as $skukey=>$skuname){


// print_r($position);exit;
//$_GET['sku']; //not really safe to read from $_GET but for test purposes it will do.
$id = Mage::getModel('catalog/product')->getIdBySku($skuname); //get the product id.

if(!$id)
continue;

  echo $sql = "UPDATE {$tableName} SET `position` = '".$position[$skukey]."' WHERE `product_id` = {$id}";

  //set the position to 0 for the product in all the categories.
 //run the query
$connection->query($sql);

}

/*

$product_sku = "tshirt-girls-sansani";
$product_id = Mage::getModel("catalog/product")->getIdBySku( $product_sku );
$product = Mage::getModel("catalog/product")->load( $product_id );
echo $product_name = $product->getName();echo '<br>';
echo $product_image = $product->getImageUrl();echo '<br>';
echo $product_code = $product->getDesignCode();
*/
/* $cat = Mage::getModel('catalog/category')->load(4);
$subcats = $cat->getChildren();
foreach(array_reverse(explode(',',$subcats)) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive()) {
	  $url =  $_category->getURL();
	  $name = $_category->getName();
	
   echo $name."<br>";
  }
} */
/*

$menuStr = '';
$sessionCustomer =Mage::getSingleton("customer/session");
		if ($sessionCustomer->isLoggedIn()) {
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLoginUrl().'"><i class="mi mi-Login"></i>Login</a></li>';
		}	
			
			
		
		 else{ 
			$menuStr = $menuStr.'<li><a href="'.Mage::helper('customer')->getLogoutUrl().'"><i class="mi mi-logout"></i>Logout</a></li>';
		 } 
/* $cat_id = 154;

$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true)->addAttributeToSort('position');


foreach ($collection as $cat) {
    echo $cat->getId().' '.'position'.  $cat->getPosition()."<br>";
} */

/* $cat_id = 154;
$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = Mage::getModel('catalog/category')->getCategories($cat_id, 0, true, true);

foreach ($collection as $cat) {
    echo $cat->getId().' '.$cat->getPosition()."<br>";
} */
/*
$categoryId = 292;    
$products = Mage::getModel('catalog/category')->load($categoryId)
->getProductCollection()
->addAttributeToSelect('*')
->addAttributeToFilter(
    'status',
    array('eq' => Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
);
// echo "<pre>";
// print_r($products);
 foreach($products as $product){
	$productid =  $product->getId();
	 
	 $productt = Mage::getModel('catalog/product')->load($productid);
	 
	 //echo Mage::getModel('catalog/product_media_config')->getMediaUrl( $productt->getImage())."<br>";
	  echo $productt->getSku()."<br>";
	 
}
*/

 
 
 // echo "<pre>";           
 // print_r ($products);
  



// foreach ($collection as $product) {
    //get associated products (different sizes etc)
	//echo $product->getId();die;
    // $associatedProducts = Mage::getModel('catalog/product_type_configurable')->load($product->getId());

    // foreach ($associatedProducts as $simple) {
        //load product
        // echo $simple->getSku()."<br>";
		
		//$simple_product = Mage::getModel('catalog/product')->load($simple->getId());
		

        

    // }
// }


// foreach ($collection as $product){
   // $prod_id = $product->getSku();
    // echo $prod_id;
	// echo "<br>";
    
	




/*$ids = array("85726","85748","85749","85752","85756","85763","85767","85777","103274","103298","201740","85727","85739","85747","85750","85751","85757","85764","85768","85772","85778","85728","85758","85765","85769","85773","103275","201733","201734","85729","85738","85753","85759","85766","85774","103297","85730","85754","85760","85775","103276","201723","201735","85733","85737","85755","85761","85770","85776","85734","85762","85771","103277","201736","201739","85735","85741","201737","201738","85736","85740","85732","103278","85742","103270","85731","85743","85744","103271","85745","103272","85746","103273","103279","103280","103281","103282","103283","103284","103285","103286","103287","103288","103289","103290","103291","103292","103293","103294","103295","103296","201729","201730","201732","201745","201731");
*/


//print_r($prdIds);
/*foreach($prdIds as $product_id); {
 $obj = Mage::getModel('catalog/product');
 $_product = $obj->load($product_id);
 // get Product's name
 $productid = $_product->getId();
 
}

$loadpro=Mage::getModel('catalog/product')->load($productid);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($loadpro->getId());

foreach($mediaApiItems as $item){
$datatemp=$mediaApi->remove($loadpro->getId(), $item['file']);

}
$loadpro->save();*/
 // $catID = $current_category->getId(); //or any specific category id, e.g. 5
// $children = Mage::getModel('catalog/category')->getCategories($catID);
// foreach ($children as $category) {
       // echo $category->getId();
        // echo $category->getName();
       
// }
/*$categoryIds = Mage::getModel('catalog/product')->load(75); 
$productSku = array();
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	$prod_id = array(); 	
	foreach($collection as $product) 
	{
		//echo $prod_id = $product->getName();
		if(!in_array($product->getSku(),$productSku))
		$productSku[] = $product->getSku();
		//echo "<br>";
	}	
}
echo '<pre>';exit;
print_r($productSku);
exit;*/
/*$productId=85781;
$_product = Mage::getModel('catalog/product')->load($productId);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
try {
    $items = $mediaApi->items($_product->getId());
    foreach($items as $item) {
      
		$mediaApi->remove($_product->getId(), $item['file']);
	
    }
} catch (Exception $exception){
    var_dump($exception);
    die('Exception Thrown');
}*/


/*$productId=85779;
$product = Mage::getModel('catalog/product')->load($productId);
$fullImgPath = 'Add full path of the image that you want add';
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($product->getId());
$attributes = $product->getTypeInstance()->getSetAttributes();
$gallery = $attributes['media_gallery'];
if (count($mediaApiItems) > 0) {
    foreach($mediaApiItems as $item){
            
			$mediaApi->remove($product->getId(), $item['file']);
            //deleting image source
            if($gallery->getBackend()->getImage($product, $item['file'])) {
               $gallery->getBackend()->removeImage($product, $item['file']);
            }
    }
    $product->save();
}*/




 //before adding need to save product
// change mobile case model name
/*echo '<pre>';
$folder = 'E:\xampplite\htdocs\projects\Active\jazzy1\media\blank_images/';
//print_r($_SERVER);
$target = $folder.'mobile-case-name-change/';
$weeds = array('.', '..'); 
$directories = array_diff(scandir($target), $weeds);
$foldername = array_values($directories); 
//print_r($foldername);

$designername = array();
$lines = file($folder.'designer.csv');

foreach ($lines as $key => $value)
{
    $designername[$key] = ($value);
}
//print_r($designername);

$magento = array();
$lines = file($folder.'magento.csv');

foreach ($lines as $key => $value)
{
    $magento[$key] = ($value);
}
//print_r($magento);

for($i=0;$i<count($foldername);$i++)
{
	$name = trim($foldername[$i]);	
	for($j=0;$j<count($designername);$j++)
	{
		if(trim($designername[$j]) == $name)
		{	
			$new_name = trim($magento[$j]);
			echo $target.$name;
			echo '  =>  ';
			echo $target.$new_name;
			if($new_name != '')
			{
				$r = rename($target.$name, $target.$new_name);
			}
			if($r == 0)
			{
				$fp = fopen($folder."not_found.txt", "a");
				fwrite($fp, $new_name."\r\n");
			}
			echo ' <br> ';
		}
     }
    //echo $r;die;
}*/

// delete category from asus

// Mage::register("isSecureArea", 1);
// for($i=1980;$i<2914;$i++)
// {		
	// Mage::getModel("catalog/category")->load($i)->delete();
	// echo $i." delete";
	// echo '<br>';
// }

/*$cat_id = 34; // a category id that you can get from admin
//$category = Mage::getModel('catalog/category')->load($categoryId);

$category = Mage::getModel('catalog/category')->load($cat_id); //put your category id here
$child = $category->getChildren();
if($child != '')
{
	$children = explode(",", $child);
	for($i=0;$i<count($children);$i++)
	{
		$sub_cat = Mage::getModel('catalog/category')->load($children[$i]);
		//print_r($sub_cat);die;
		echo '<strong>'.$sub_cat->getName().'</strong>';
		echo '<br>';
		$sub_cat_child = $sub_cat->getChildren();
		if($sub_cat_child != '')
		{
			$sub_cat_children = explode(",", $sub_cat_child);
			for($j=0;$j<count($sub_cat_children);$j++)
			{
				$sub_sub_cat = Mage::getModel('catalog/category')->load($sub_cat_children[$j]);
				echo $sub_sub_cat->getName();
				echo '<br>';
			}
		}
	}
}*/

// custom option



//For case 
/*$categoryIds = array(34);		
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	//$prod_id = array(); 	
	foreach($collection as $product) 
	{
		$prod_id = $product->getId();
		$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
		$rowArray = $read->fetchRow($select); 
		$val_id = $rowArray['value_id'];
		if(isset($val_id) && $val_id != '')
		{
			$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area'))->where('value_id = '.$val_id);
			$select_val_row = $read->fetchRow($select_val); 
			$design_val = $select_val_row['design_area'];
			
				$write->beginTransaction();
				$fields = array();
				$fields['label'] = 'Front';
				$fields['position'] = 1;
				$fields['disabled'] = 0;
				$fields['color'] = 0;
				$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"7f9aUF5koApiVC31"}';
				$where = $write->quoteInto('value_id =?', $val_id);
				$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
				$write->commit();
			
		}
	}
	echo $categoryIds[$cat].' Complete';
	echo '<br>';
}*/



/*$prod_collection = Mage::getModel('catalog/category')->load(3);
$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
foreach($collection as $product)
{
	$pid = $product->getId();
	//echo $pid;die;
	$chk_option_qry = $read->query("SELECT `option_id` FROM `catalog_product_option` WHERE `product_id` = '".$pid."'");
	$chk_option_res = $chk_option_qry->fetch();
	if($chk_option_res['option_id'] == '')
	{
		//insert brand
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 1)");
		$last_boption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_boption_id."', 0, 'Brand')");	
			
		$brand_arr = array("Apple", "Samsung", "Google", "Htc", "Lg", "Motorola", "Sony", "Xiaomi", "Nokia", "Blackberry", "Asus", "Lenovo", "Oppo", "Gionee");
		for($i=0;$i<14;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_boption_id."', '', '".$i."')");
			$last_option_btype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_btype_id."', 0, '".$brand_arr[$i]."')");
		}
		
		// insert type
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 0)");
		$last_toption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_toption_id."', 0, 'Type')");	
				
		$type_arr = array("Mobile Case", "Mobile Skin");
		for($i=0;$i<2;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_toption_id."', '', '".$i."')");
			$last_option_ttype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_ttype_id."', 0, '".$type_arr[$i]."')");
		}
		
		// insert Model
		
		$write->query("INSERT INTO `catalog_product_option` (`product_id`, `type`, `is_require`, `sku`, `max_characters`, `file_extension`, `image_size_x`, `image_size_y`, `sort_order`) VALUES ('".$pid."', 'drop_down', 0, '', 0, '', 0, 0, 2)");
		$last_moption_id = $write->lastInsertId();
	
		$write->query("INSERT INTO `catalog_product_option_title` (`option_id`, `store_id`, `title`) VALUES ('".$last_moption_id."', 0, 'Model')");	
				
		$model_arr = array("iPhone 6","iPhone 5c","iPhone 4&4s","iPad Mini/Retina","iPad Air","iPad 4","iPhone 6 Plus","iPad 3","iPad 2","iPhone 5/5s","iPad","iPad Air 2","iPad Mini 2","iPod Touch 4","iPodTouch 5","Galaxy Grand Duos","Galaxy S Advanced","Galaxy S Duos","Galaxy S Duos 2 (S7582) & Trend Plus (S7580)","Galaxy S5","Galaxy S4 Mini","Galaxy S3","Galaxy Grand 2 G7106","Galaxy Grand I9080/I9082","Galaxy Mega 5.8","Galaxy Note 2 N7100","Galaxy Note 3","Galaxy Note 4","Galaxy S4","Galaxy S2","Galaxy S3 Mini","Galaxy S5 Mini","Galaxy Mega 6.3I9200","Galaxy Note 8.0","Galaxy Note 10.1","Galaxy Ace","Galaxy Ace Plus","Galaxy Ace 2","Galaxy Ace 3","Galaxy Ace 4","Galaxy Tab 2","Galaxy Tab3 7.0","Galaxy Tab3 8.0","GalaxyTab3 10.1","Galaxy Tab 7.0 Plus","Galaxy Tab 7.7","Galaxy Core","Galaxy Win","Samsung Trend 2 Duos","Galaxy Nexus","Galaxy Y","Galaxy Alpha","Galaxy Xcover 2","Lumia 620","Lumia 920","Lumia 520","BB Z10","BB Q10","BB 9900","Nexus 6","Nexus 5","Nexus 4","Nexus 7","HTC Desire 816","HTC One M7","HTC One M8","HTC One M8 Mini","HTC One S","HTC One X","HTC Sensation XL G21","Lg GoogleNexus 5","Lg Google Nexus 4","LG OPTIMUS G E975","LG OPTIMUS L5 II (E460)","LG G2","LG G3","LG L70","LG L90","Moto E","Moto G","Moto G2","Moto razr d1","Moto razr d3","Moto X","Moto X2","Moto Google Nexus 6","Xperia Sp","Xperia M2","Xperia Z","Xperia Z1","Xperia Z2","Xperia Z3","Xperia Z1 mini","XIAOM mi2","XIAOMI Redmi 1S","Google Nexus 7","Google Nexus 8","Google Nexus 9","Google Nexus 10");
		for($i=0;$i<84;$i++)
		{
			$write->query("INSERT INTO `catalog_product_option_type_value` (`option_id`, `sku`, `sort_order`) VALUES ('".$last_moption_id."', '', '".$i."')");
			$last_option_mtype_id = $write->lastInsertId();
			
			$write->query("INSERT INTO `catalog_product_option_type_title` (`option_type_id`, `store_id`, `title`) VALUES ('".$last_option_mtype_id."', 0, '".$model_arr[$i]."')");
		}
	}
	echo $pid." complete";
	echo '<br>';
	
}*/
?>