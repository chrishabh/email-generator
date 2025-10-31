<?php

namespace App\Console\Commands;

use App\Notifications\ConfirmationCode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendBounceeNotificationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendBounceeNotificationEmails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification emails to Bouncee users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $emails = [
            "wvwvdabwbeb@gmail.com",
            "sairam5555@gmail.com",
            "abegayljordan247@gmail.com",
            "bhattacharjee.abir1892@gmail.com",
            "Harperlily56y@gmail.com",
            "nsnshdnjddj@gmail.com",
            "ag6532667@gmail.com",
            "akashnaidu40557@gmail.com",
            "blessingakindele123@gmail.com",
            "alex@yopmail.com",
            "vkem2868@gmail.com",
            "alferidabrar40@gmail.com",
            "jonston480@gmail.com",
            "ampeside82@gmail.com",
            "hello@anandkjha.com",
            "anilnehra1990@gmail.com",
            "kooljaat18@gmail.com",
            "laxmmibombakshay@gmail.com",
            "anjaliumesh858@gmail.com",
            "anjalidhurve7491@gmail.com",
            "antonio@antoniotheaffiliate.com",
            "salesskwebglobal@gmail.com",
            "ashwiniash665@gmail.com",
            "aswinsareesh003@gmail.com",
            "discoverhm@gmail.com",
            "work.ayishnadeem@gmail.com",
            "royal123@gmail.com",
            "sheshu7842@gmail.com",
            "bbgg51829@gmail.com",
            "chhcufbdhd@gmail.com",
            "benjensnow.595883@gmail.com",
            "bjjvjkk07@gmail.com",
            "smithbridget036@gmail.com",
            "bryan.haver@adyen.com",
            "ncncbxnx915@gmail.com",
            "nddmkdbxnxj@gmail.com",
            "wocrih@imail.edu.vn",
            "vbjbgf114@gmail.com",
            "googab23@gmail.com",
            "ghbt145@gmail.com",
            "ksssjdd01@gmail.com",
            "chaithra1122@gmail.com",
            "Chaithra8317@gmail.com",
            "chrisrormey25@gmail.com",
            "connectwellinformation@gmail.com",
            "shabbirali05gg@gmail.com",
            "rossendarren@gmail.com",
            "daryl_dacut@yahoo.com",
            "dataseller259@gmail.com",
            "dvd44103@gmail.com",
            "ahmedalibhai614@gmail.com",
            "amindeeksha14@gmail.com",
            "robartjames682@gmail.com",
            "deva@bizdemandgen.com",
            "dilip@extracables.com",
            "mohetah547@asimarif.com",
            "yahigiy608@asimarif.com",
            "axarapatel815@gmail.com",
            "dnbddnbdnd@gmail.com",
            "janidod41@gmail.com",
            "antoinette.connelly@validic.com",
            "eurontyrell.571112@gmail.com",
            "opgg5103@gmail.com",
            "shohidfahim5022@gmail.com",
            "kingmalik447@gmail.com",
            "lilybrown11000@gmail.com",
            "fatimach0730@gmail.com",
            "donfoji622@gmail.com",
            "gy526860@gmail.com",
            "gerald.abigail@outlook.com",
            "yoyoa9296@gmail.com",
            "govindhan.p@outboundpts.com",
            "hamida.begum08@gmail.com",
            "2022ali116@gmail.com",
            "chinni4455@gmail.com",
            "Hari1510@gmail.com",
            "Hari6666@gmail.com",
            "ifadafavour50@gmail.com",
            "Kariukiisaac2014@gmail.com",
            "ivanovskajaglika@gmail.com",
            "das.dgc@gmail.com",
            "jatin.gupta@aerenoutsourcing.com",
            "jdjdkgg563@gmail.com",
            "snjdjd2873@gmail.com",
            "jennifer.lada@bizdmd.com",
            "jerahmayaq@gmail.com",
            "jexay55325@advitize.com",
            "jgfybbt@gmail.com",
            "jffj3802@gmail.com",
            "johnhezel123@gmail.com",
            "ohiolejoseph70@gmail.com",
            "hasnainleadsflow@gmail.com",
            "john@datacorpbizz.com",
            "yashika@demandgen-biz.com",
            "yashitha@demandgen-biz.com",
            "kamalczar@gmail.com",
            "chinnavijay03952@gmail.com",
            "sinetowin@rediffmail.com",
            "kariearl0000@gmail.com",
            "kartikvishwakarma183@gmail.com",
            "katecamara91@gmail.com",
            "killianbrown11000@gmail.com",
            "ouggkkjg@gmail.com",
            "klashh649@gmail.com",
            "leolopez@ponlo.com",
            "lhjkvb742@gmai.com",
            "husson.lisa@gmail.com",
            "lisa.reggio@bizdmd.com",
            "lokesh@bizdemandgen.com",
            "ls387467@gmail.com",
            "dj4854448@gmail.com",
            "mahavirsilicon@gmail.com",
            "maheshvreddy1997@gmail.com",
            "maheshvreddy97@gmail.com",
            "mnmani44@gmail.com",
            "manishatambe18@gmail.com",
            "manju@aidash.co",
            "manneru85@gmail.com",
            "advocatemantharkhan@gmail.com",
            "mayan_o.t@icloud.com",
            "mayankbhardwaj7884@gmail.com",
            "masud6874.net@gmail.com",
            "himf2558@gmail.com",
            "milindkapale@speedtech.ai",
            "minukuvikram@gmail.com",
            "chshazi630@gmail.com",
            "bhaikhan73806@gmail.com",
            "israilumar@gmail.com",
            "moneyexperts99@gmail.com",
            "habeebkalyar@gmail.com",
            "junaid@ikonicdev.com",
            "02emailveri@urdufunclub.org",
            "bds77386@gmail.com",
            "jessubds@gmail.com",
            "nasrin.reza.mba@gmail.com",
            "neerajgadkari25@gmail.com",
            "nehanehra0712@gmail.com",
            "anjehohngiamagdalene@gmail.com",
            "totoro8505@cristout.com",
            "nvajala@gmail.com",
            "nsnsjjsjs711@gmail.com",
            "nugrohoy@gmail.com",
            "gold48300@gmail.com",
            "samstringsamuel@gmail.com",
            "ANGELAMIKE566@gmail.com",
            "nps@parthatechnologies.com",
            "bridgetjamie741@gmail.com",
            "jillmansell68@gmail.com",
            "benedictapeace64@gmail.com",
            "gladysbrown725@gmail.com",
            "petersonvector945@gmail.com",
            "poraspathak@gmail.com",
            "mahourpriyanka112@gmail.com",
            "timt25162@gmail.com",
            "anayasheikh876900@gmail.com",
            "muhammadhasnain0146@gmail.com",
            "rhitvfghtj@gmail.com",
            "jooosooraj@gmail.com",
            "Raghujalgar001@gmail.com",
            "rahitsingha007@gmail.com",
            "rajatmunshi@hotmail.com",
            "rajendraprasadnaidu73@gmail.com",
            "t.r.p.n95@gmail.com",
            "rajesh@skwebglobal.com",
            "lpcrazylove7@gmail.com",
            "rambalram12@gmail.com",
            "rr2457078@gmail.com",
            "rambokemosabe@gmail.com",
            "adekunleaderibigbe@gmail.com",
            "jessu8558@gmail.com",
            "googabroo@gmail.com",
            "uddbjfghdy232@gmail.com",
            "arshad22ch@gmail.com",
            "tugjcivxdij@gmail.com",
            "bhavitha064@gmail.com",
            "g82636007@gmail.com",
            "l80201158@gmail.com",
            "renukakathi98@gmail.com",
            "S17374367@gmail.com",
            "s49258084@gmail.com",
            "sravani8967@gmail.com",
            "richardedwardd4@gmail.com",
            "tylorn93@gmail.com",
            "soft.eng.rishabh@gmail.com",
            "rkateliya@ismg.io",
            "robertb5128@gmail.com",
            "hasnainali46f@gmail.com",
            "bhosale.31sachin@gmail.com",
            "sammartin12may@gmail.com",
            "samkd304@gmail.com",
            "sandeepky322@gmail.com",
            "saurabh@skwebglobal.com",
            "sayalideshpande801@gmail.com",
            "kartikmalhotra02345@gmail.com",
            "mahimetha3@gmail.com",
            "tambolishahrukh143@gmail.com",
            "shawn@inroomspipeline.store",
            "selledge@salesleadautomation.com",
            "sheetalshende28@gmail.com",
            "shivaipo@gmail.com",
            "dimpledimple7361@gmail.com",
            "bujjishobha8@gmail.com",
            "nimmu123@gmail.com",
            "prasadnaidurajendra348@gmail.com",
            "queen1510@gmail.com",
            "valmikibalaram7@gmail.com",
            "qwdfga482@gmail.com",
            "smit@leadgenxperts.com",
            "smitmulani@gmail.com",
            "sunnytuns24@gmail.com",
            "sophiabrown11000@gmail.com",
            "stephakay85@gmail.com",
            "kundu.soniak.sudeshna7@gmail.com",
            "suganthi2394@gmail.com",
            "sohikhan309@gmail.com",
            "ammanannagirl@gamail.com",
            "andiberg003@gmail.com",
            "dimplequeen2471@gmail.com",
            "sujidaring8@gmail.com",
            "sumayya.kabeer@gadgeon.com",
            "sunnykirttaniya345@gmail.com",
            "mounikatadela1007@gmail.com",
            "adeyemitaiwo825@gmail.com",
            "barbakadzetamo24@gmail.com",
            "thomasgrew719@gmail.com",
            "urbe1835@gmail.com",
            "robertdavis09136@gmail.com",
            "adenijitomiwa638@gmail.com",
            "rb458771@gmail.com",
            "tulasiamadasani@gmail.com",
            "oyebabas019@gmail.com",
            "gh067167@gmail.com",
            "udithbabuvarrier10@gmail.com",
            "Umashankzgti@gmail.com",
            "umasreeling@gmail.com",
            "umer.farooq92911@gmail.com",
            "umer.farooq92960@gmail.com",
            "usurmdj@gmail.com",
            "kasivanarasi123@gmail.com",
            "varsun26@gmail.com",
            "ghhvf9488@gmail.com",
            "vggy45700@gmail.com",
            "vinod.tiwari@expresstechsoftwares.com",
            "v3082326@gmail.com",
            "viveka@f6s.com",
            "waheedahme.acs@gmail.com",
            "waqasakbar102000@gmail.com",
            "waqasleadsflow@gmail.com",
            "workwpa@gmail.com",
            "udidujdjd6@gmail.com",
            "jannhasnain89@gmail.com",
            "hasnainfadt@gmail.com",
            "iqra41noor@gmail.com",
            "Whstou01@gmail.com",
            "harad517@gmail.com",
            "goga998g@gmail.com",
            "gogah0914@gmail.com",
            "ghhg49619@gmail.com",
            "yashvispute555@gmail.com",
            "y8254268@gmail.com",
            "alextrans19881988@gmail.com",
            "23-ee-5@students.uettaxila.edu.pk"
        ];
        $template = env("NOTIFICATION_TEMPLATE");
        $subject = env("NOTIFICATION_SUBJECT");

        if(env('NOTIFICATION_ACTION')){
           
            $count = 0;
           
            foreach($emails as $value)
            {
                Notification::route('mail', $value)->notify(new ConfirmationCode($subject,[],$template));
                echo "Email Triggered to: " .$value; 
                echo "\n"; 
                $count++;
            }
            echo "Total Email Sent:" .$count; 
            echo "\n"; 
        }else{
            $email = env('TEST_EMAIL');
            echo "Test Email Triggered:" .$email; 
            echo "\n"; 
            Notification::route('mail', $email)->notify(new ConfirmationCode($subject,[],$template));
        }
    }
}
