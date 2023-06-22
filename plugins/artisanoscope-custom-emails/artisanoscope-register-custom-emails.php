<?php
/**
* Plugin Name: Artisanoscope Custom Emails
* Description: Pour l'envoi d'emails spécifiques à l'Artisanoscope
* Author: Diane Binsztok
* Version: 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
return;
}

//define( 'CUSTOM_WC_EMAIL_PATH', plugin_dir_path( __FILE__ ) );

//(Syntaxe avec indication des types: du param et de la valeur de retour)
function artisanoscope_register_custom_email_classes( array $email_classes ): array {

    //Email de rappel: atelier dans sept jours
    require_once('classes/class-wc-email-customer-workshop-reminder-seven-days.php');
    //Email de rappel: atelier dans un jour
    require_once('classes/class-wc-email-customer-workshop-reminder-one-day.php');
    //Email de feedback: récolter des avis des participants
    require_once('classes/class-wc-email-customer-workshop-feedback.php');

    $email_classes['WC_Email_Customer_Workshop_Reminder_Seven_days'] = new WC_Email_Customer_Workshop_Reminder_Seven_days();
    $email_classes['WC_Email_Customer_Workshop_Reminder_One_Day'] = new WC_Email_Customer_Workshop_Reminder_One_Day();
    $email_classes['WC_Email_Customer_Workshop_Feedback'] = new WC_Email_Customer_Workshop_Feedback();

    return $email_classes;
}
//Ajouter les classes custom via le hook de filtre sur 'woocommerce_email_classes'
add_filter( 'woocommerce_email_classes', 'artisanoscope_register_custom_email_classes',90,1);