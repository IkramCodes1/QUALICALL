<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Conversation;
use App\Models\Dialogue;

class ConversationSeeder extends Seeder
{
    /**
     */
    

     public function run()
     {
         $conversation = Conversation::create([
             'name' => 'INPI Call',
             'path' => 'audios/audio1.wav',
             'duration' => 224, // ~3:44
             'call_date' => now(),
             'societe_id' => 1
         ]);
     
         $dialogues = [
             ['actor'=>'Client','text'=>'Pas de texte','start'=>2,'end'=>3],
             ['actor'=>'Agent','text'=>'Une file directe, bonjour.','start'=>4,'end'=>5],
             ['actor'=>'Client','text'=>'Oui, bonjour.','start'=>6,'end'=>7],
             ['actor'=>'Client','text'=>'Monsieur Cessa, là, pareil.','start'=>7,'end'=>8],
             ['actor'=>'Agent','text'=>'Très bien.','start'=>9,'end'=>10],
     
             ['actor'=>'Client','text'=>"Je voulais avoir une information parce que je dois envoyer un contrat sur mon espace INPI.",'start'=>13,'end'=>20],
             ['actor'=>'Client','text'=>"Parce que j'ai un dossier en attente de régularisation.",'start'=>24,'end'=>27],
             ['actor'=>'Client','text'=>"Je ne sais pas comment envoyer ce contrat-là.",'start'=>30,'end'=>32],
     
             ['actor'=>'Agent','text'=>"Un contrat demandant, c'est ça ?",'start'=>33,'end'=>36],
             ['actor'=>'Client','text'=>"Un contrat pour un changement d'activité.",'start'=>36,'end'=>38],
             ['actor'=>'Agent','text'=>"Mais pourquoi un contrat pour un changement d'activité ?",'start'=>39,'end'=>41],
             ['actor'=>'Client','text'=>"D'accord.",'start'=>41,'end'=>41],
     
             ['actor'=>'Agent','text'=>"Alors, on va voir ça ensemble.",'start'=>42,'end'=>43],
             ['actor'=>'Agent','text'=>"Votre nom, votre prénom, s'il vous plaît.",'start'=>45,'end'=>47],
             ['actor'=>'Client','text'=>"S-E-D-S-A, Raphaël.",'start'=>48,'end'=>49],
     
             ['actor'=>'Agent','text'=>"Numéro téléphone, adresse e-mail ?",'start'=>51,'end'=>53],
             ['actor'=>'Client','text'=>"06 822 622 73.",'start'=>53,'end'=>55],
             ['actor'=>'Client','text'=>"rafael83@gmail.com.",'start'=>57,'end'=>59],
     
             ['actor'=>'Agent','text'=>"D'accord.",'start'=>60,'end'=>61],
             ['actor'=>'Agent','text'=>"Code postal ?",'start'=>63,'end'=>64],
             ['actor'=>'Agent','text'=>"423 320.",'start'=>64,'end'=>68],
     
             ['actor'=>'Agent','text'=>"Pourquoi vous avez besoin d'un contrat ?",'start'=>68,'end'=>71],
             ['actor'=>'Agent','text'=>"Je ne comprends pas.",'start'=>71,'end'=>71],
             ['actor'=>'Client','text'=>"Alors, en fait...",'start'=>72,'end'=>73],
     
             ['actor'=>'Agent','text'=>"Ah, contrat d'agent !",'start'=>74,'end'=>76],
             ['actor'=>'Agent','text'=>"Oui, c'est vrai.",'start'=>76,'end'=>78],
             ['actor'=>'Agent','text'=>"Vous faites régulariser la formalité.",'start'=>79,'end'=>80],
             ['actor'=>'Agent','text'=>"Vous allez dans pièce jointe.",'start'=>81,'end'=>83],
     
             ['actor'=>'Client','text'=>"Je suis sur l'espace.",'start'=>84,'end'=>88],
             ['actor'=>'Client','text'=>"Il y a marqué en attente de régularisation.",'start'=>93,'end'=>99],
     
             ['actor'=>'Agent','text'=>"Il faut appuyer sur régulariser la formalité.",'start'=>100,'end'=>103],
             ['actor'=>'Client','text'=>"Je ne l'ai pas.",'start'=>106,'end'=>107],
             ['actor'=>'Client','text'=>"Il y a juste un justificatif.",'start'=>107,'end'=>109],
     
             ['actor'=>'Agent','text'=>"Vous êtes sur téléphone ?",'start'=>111,'end'=>112],
             ['actor'=>'Client','text'=>"Non sur ordinateur.",'start'=>116,'end'=>118],
             ['actor'=>'Agent','text'=>"Quel navigateur ?",'start'=>118,'end'=>121],
             ['actor'=>'Client','text'=>"Google Chrome.",'start'=>122,'end'=>124],
     
             ['actor'=>'Agent','text'=>"Cliquez sur la formalité.",'start'=>132,'end'=>137],
             ['actor'=>'Agent','text'=>"Puis régulariser.",'start'=>138,'end'=>140],
     
             ['actor'=>'Client','text'=>"J'ai deux formalités.",'start'=>151,'end'=>154],
             ['actor'=>'Client','text'=>"Une validée, une en attente.",'start'=>154,'end'=>157],
     
             ['actor'=>'Agent','text'=>"Cliquez sur régulariser.",'start'=>161,'end'=>164],
     
             ['actor'=>'Agent','text'=>"Vous avez pièce jointe.",'start'=>166,'end'=>168],
             ['actor'=>'Agent','text'=>"Cliquez dessus.",'start'=>168,'end'=>171],
     
             ['actor'=>'Client','text'=>"Ce sont des brouillons.",'start'=>193,'end'=>196],
             ['actor'=>'Agent','text'=>"Non non.",'start'=>197,'end'=>198],
     
             ['actor'=>'Client','text'=>"Ça vous fait chier ou quoi ?",'start'=>202,'end'=>203],
             ['actor'=>'Client','text'=>"Je rappelle demain.",'start'=>203,'end'=>205],
             ['actor'=>'Client','text'=>"On arrête là.",'start'=>210,'end'=>213],
             ['actor'=>'Client','text'=>"Au revoir.",'start'=>223,'end'=>224],
         ];
     
         foreach ($dialogues as $d) {
             Dialogue::create([
                 'actor' => $d['actor'],
                 'text' => $d['text'],
                 'start' => $d['start'],
                 'end' => $d['end'],
                 'conversation_id' => $conversation->id
             ]);
         }
     }
}
