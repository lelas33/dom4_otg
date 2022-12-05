<?php


// Ensemble de fonction de communication avec les centrales DOM 2
// ==============================================================


// Fonction d'envoi d'un message par la com TCP/IP
// -----------------------------------------------
function dom2_message_send($socket, $msg, &$ack)
{

  $lg_mess_max = 2500;

  // Preparation du message
  $tab_param = str_repeat(chr(32),$lg_mess_max+8);   // longueur trame message max : 4 + 4 + lg_mess_max

  $tab_param[0] = chr(( $msg['cmd']) & 0x000000ff) ;
  $tab_param[1] = chr((($msg['cmd']) & 0x0000ff00) >> 8) ;
  $tab_param[2] = chr((($msg['cmd']) & 0x00ff0000) >>16) ;
  $tab_param[3] = chr((($msg['cmd']) & 0xff000000) >>24) ;
  $tab_param[4] = chr(( $msg['nbp']) & 0x000000ff) ;
  $tab_param[5] = chr((($msg['nbp']) & 0x0000ff00) >> 8) ;
  $tab_param[6] = chr((($msg['nbp']) & 0x00ff0000) >>16) ;
  $tab_param[7] = chr((($msg['nbp']) & 0xff000000) >>24) ;
  if ( $msg['nbp'] != 0 ) {
    for ($i = 0; $i<$msg['nbp']; $i++) {
      $tab_param[8+$i] = chr($msg['param'][$i]) ;
      }
    }

  // Envoi du message
  $lg_mess = 8 + $msg['nbp'];   // envoi de l'entete du message, et de ses 'nb_par' parametres
  socket_send ( $socket, $tab_param, $lg_mess, 0 ) ;
//  echo ( " long mess :  $lg_mess <br>" ) ;
//  for ( $i=0; $i<$lg_mess; $i++) {
//    echo ( " i:$i => ".ord($tab_param[$i]) ) ;
//    }

  // Attente de l'acquittement
  $err = 0 ;
  //   Attente message retour en 2 temps : entete de 8 octets, puis parametres
//  $lg = socket_recv ( $socket, &$tab_param, $lg_mess, MSG_WAITALL ) ;
//  $lg = socket_recv ( $socket, &$tab_param, 8, MSG_WAITALL ) ;
  $lg = socket_recv ( $socket, $tab_param, 8, MSG_WAITALL ) ;

//  echo ( "recu : $lg <br>" ) ;
//  for ( $i=0; $i<$lg; $i++) {
//    $cd = ord($tab_param[$i]) ;
//    echo ( " [$i] => $cd <br>" ) ;
//    }
  if ( ($tab_param[3] != chr(0xFF)) || ($tab_param[0] != chr($msg['cmd'])) ) {
    $err = 1 ;
    echo ( "<br>Erreur message retour<br>");
    return $err ;
    }

  // Mise en forme du message de retour
  $ack['cmd'] = ( (ord($tab_param[3]) <<24) | (ord($tab_param[2]) <<16)
                | (ord($tab_param[1]) << 8) |  ord($tab_param[0]) ) ;
  $ack['nbp'] = ( (ord($tab_param[7]) <<24) | (ord($tab_param[6]) <<16)
                | (ord($tab_param[5]) << 8) |  ord($tab_param[4]) ) ;
  if ( $ack['nbp'] != 0 ) {
    if ($ack['nbp'] >= $lg_mess_max) $ack['nbp'] = $lg_mess_max;
    $lg = socket_recv ( $socket, $tab_param, $ack['nbp'], MSG_WAITALL ) ;
    for ($i=0; $i<$ack['nbp']; $i++)
       $ack['param'][$i] = ord($tab_param[$i]) ;
    }

  //echo ("Erreur : $err <br>" ) ;
  return $err ;
 }


// Ouverture d'un socket vers DOM2
// -------------------------------
function dom2_start_socket ($dest)
{
  // Creation d'une liaison TCP/IP avec le serveur vers la centrale DOM
  //-------------------------------------------------------------------
  // Creation d'un socket
  $socket = socket_create(AF_INET, SOCK_STREAM, 0) ;
  //echo "  Socket : $socket<br>" ;

  // connect to socket
  if ($dest == "A") {
    $host = HOST_DOM2A_IP;     // Connection sur adresse IP dom2 abri
    $port = HOST_DOM2A_PO;
  }
  else if ($dest == "G") {
    $host = HOST_DOM2G_IP;     // Connection sur adresse IP dom2 garage
    $port = HOST_DOM2G_PO;
  }

  $err = socket_connect($socket, $host, $port) ;
  //echo "  Connect erreur : $err<br>" ;
  return $socket;
}

// Fermeture d'un socket vers DOM2
// -------------------------------
function dom2_end_socket ($socket)
{

  // Envoi message deconnection
  $tab_param  = '';
  $tab_param .= chr(0xff);
  $tab_param .= chr(0x00);
  $tab_param .= chr(0x00);
  $tab_param .= chr(0xff);
  socket_send ( $socket, $tab_param, 4, 0 ) ;

  // Fermeture socket
  socket_shutdown($socket, 2) ;
  socket_close($socket) ;

}

?>
