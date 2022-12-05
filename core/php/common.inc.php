<?php

  define('COM_DOM2', "Done");   // constant defined if DOM2 constants are defined

// definition des addresses IP des peripheriques
// =============================================
  // DOM2 Abri
  // define('HOST_DOM2A_IP', "192.168.1.22");   // Filaire
  // define('HOST_DOM2A_PO', 30001);
  // DOM2 Garage
  define('HOST_DOM2G_IP', "192.168.1.6");
  define('HOST_DOM2G_PO', 30001);


// Liste des commandes des messages PC pour DOM2G
// ==============================================
// Controle
  define('MCNT3_GETSTS',   0x01) ;          // Controle : Recuperation de l'ensemble des status
  define('MCNT3_KEY',      0x10) ;          // Controle : Appui touche
  define('MCNT3_GETDIS',   0x11) ;          // Controle : Lecture de l'ecran LCD (1/2)
  define('MCNT3_GETVER',   0x29) ;          // Controle : recuperation des versions firmware
  define('MCNT3_SETTEMPNA',0x2A) ;          // Controle : definition de la temperature sejour (temperature netatmo)

  // Chauffage
  define('MCHA_SETMODE',   0x30) ;          // Chauffage : Choix du mode actif et du coeff
  define('MCHA_STCMD',     0x31) ;          // Chauffage : Start d'une commande en mode manuel ou automatique
  define('MCHA_STPARAM',   0x32) ;          // Chauffage : definition des parametres du chauffage
  define('MCHA_PROGAJ',    0x33) ;          // Chauffage : Programmation du Mode Auto Jour
  define('MCHA_PROGAH',    0x34) ;          // Chauffage : Programmation du Mode Auto Hebdo
  define('MCHA_GETAJ',     0x35) ;          // Chauffage : Relecture du Programme Auto Jour
  define('MCHA_GETAH',     0x36) ;          // Chauffage : Relecture du Programme Auto Hebdo
  define('MCHA_STAT_REG',  0x37) ;          // Chauffage : Statistiques regulation chauffage
  define('MCHA_RLD_CONF',  0x38) ;          // Chauffage : Rechargement de la configuration depuis carte SD
  define('MCHA_GET_OT',    0x39) ;          // Chauffage : Capture tous les parametres courant OpenTherm
  define('MCHA_GET_STAT',  0x3A) ;          // Chauffage : retour des statistiques de consommation chauffage et ECS
  define('MCHA_SAVE_CONF', 0x3B) ;          // Chauffage : Sauvegarde de la configuration sur fichier (hors consignes)
  define('MCHA_GET_STS',   0x3C) ;          // Chauffage : Lecture du statut courant
  define('MCHA_PUSH_TEMPE',0x3D) ;          // Chauffage : Transfert des temperatures courante par piece

  // volets
  define('MTLC_OVOLET',  0x80) ;          // Telecommande : Ouverture des volets roulants
  define('MTLC_FVOLET',  0x81) ;          // Telecommande : Fermeture des volets roulants
  define('MTLC_SVOLET',  0x82) ;          // Telecommande : STOP des volets
  define('MTLC_MVOLET',  0x83) ;          // Telecommande : mode manuel des volets
  define('MTLC_EAL_ON',  0x84) ;          // Telecommande : Allumage �clairage allee
  define('MTLC_EAL_OFF', 0x85) ;          // Telecommande : Arret �clairage allee
  define('MTLC_INT_POR', 0x86) ;          // Telecommande : Interrogation sur l'etat du portail
  define('MTLC_RLD_CONF',0x87) ;          // Telecommande : Rechargement de la configuration depuis carte SD
  define('MTLC_VMC',     0x89) ;          // Telecommande : gestion du mode de la VMC

// Liste des capteurs de temperature pour DOM2G
// ============================================
  define('NB_PIECES',      12) ;            // Nombre de pieces chauffee et regulees (hypothese: 1 capteur temperature par piece)
  define('TEMP_INVALIDE', 990) ;            // Temperature invalide

// Liste des commandes des messages PC pour DOM2A
// ==============================================
// Controle
  define('MCNT2_GETSTS',   0x01) ;          // Controle : Recuperation de l'ensemble des status
  define('MCNT2_KEY',      0x10) ;          // Controle : Appui touche
  define('MCNT2_GETDIS1',  0x11) ;          // Controle : Lecture de l'�cran LCD (1/2)
  define('MCNT2_GETDIS2',  0x12) ;          // Controle : Lecture de l'�cran LCD (2/2)
  define('MCNT2_GETTEMP',  0x20) ;          // Controle : R�cup�ration des temperatures
  define('MCNT2_GETVER',   0x29) ;          // Controle : r�cup�ration des versions firmware

// Commandes pour le mode arrosage
  define('MARR2_SETMODE',  0x40) ;          // Arrosage : Choix du mode actif et du Coefficient d'arrosage
  define('MARR2_GETMODE',  0x41) ;          // Arrosage : Relecture du mode actif, du Coefficient d'arrosage et du status
  define('MARR2_STCMD',    0x42) ;          // Arrosage : Start d'une commande en mode manuel ou automatique
  define('MARR2_STPARAM',  0x43) ;          // Arrosage : D�finition des param�tres du mode Arrosage
  define('MARR2_RLD_CONF', 0x44) ;          // Arrosage : Rechargement du fichier de configuration
  define('MARR2_GETSTATE', 0x45) ;          // Arrosage : Recture de l'avancement du programme d'arrosage en cours 
  define('MARR2_SETCARR',  0x46) ;          // Arrosage : definition du coefficient d'arrosage
  
// Commandes pour le mode Eclairage
  define('MECL2_STCMD',    0x50) ;          // Eclairage : Start d'une commande en mode manuel ou automatique
  define('MECL2_STPARAM',  0x51) ;          // Eclairage : Definition des parametres du mode Eclairage
  define('MECL2_RLD_CONF', 0x52) ;          // Eclairage : Rechargement du fichier de configuration
  define('MECL2_ECLDUR',   0x53) ;          // Eclairage : Definition de la duree de l'eclairage en manuel

// Commandes pour le mode Piscine
  define('MPIS2_SETMODE',  0x60) ;          // Piscine : Choix du mode actif
  define('MPIS2_STCMD',    0x61) ;          // Piscine : Start d'une commande en mode manuel ou automatique
  define('MPIS2_STPARAM',  0x62) ;          // Piscine : D�finition des param�tres du mode piscine
  define('MPIS2_RLD_CONF', 0x63) ;          // Piscine : Rechargement du fichier de configuration
  define('MPIS2_RBDUR',    0x64) ;          // Piscine : definition de la duree de fonctionement du robot de piscine
  define('MPIS2_CONSPAC',  0x65) ;          // Piscine : definition de la consigne de la pompe a chaleur

// Commandes pour le mode Alarme
  define('MALA_STCMD',     0x70) ;          // Alarme : Start d'une commande du mode alarme
  define('MALA_STPARAM',   0x71) ;          // Alarme : Definition des parametres du mode piscine


?>
