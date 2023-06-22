<?php

//Ne fonctionne pas:
//class WC_Email_Customer_Workshop_Reminder_One_Day extends WC_Email_Customer_Workshop_Reminder
//Pour utiliser les propriétés id, title, etc, Woocommerce attend une instance de WC_Email

class WC_Email_Customer_Workshop_Reminder_One_Day extends WC_Email{

    /**
	 * Constructeur
	 */
	public function __construct() {

        // Afficher la classe custom dans l'espace admin

        // Slug de l'email: utilisable pour filtrer les autres données
        $this->id          = 'wc_email_customer_workshop_reminder_one_day';
        $this->title       = __( "Rappel d'atelier: la veille", "woocommerce" );
        $this->description = __( "Email de rappel envoyé la veille de l'atelier", "woocommerce");
        $this->customer_email = true;
        $this->heading     = __( "Prêt.e pour votre atelier de demain?", "woocommerce" );
        // Traducteurs: la variable {blogname} est un placeholder
        $this->subject     = sprintf( _x( "[%s] Votre atelier avec l'Artisanoscope", "Rendez-vous demain pour votre atelier", "woocommerce" ), "{blogname}");
        // Chemins vers le template
        $this->template_html  = "../templates/workshop-one-day-reminder-email-template.php";
        $this->template_plain = "../templates/workshop-one-day-reminder-email-template.php";
        $this->template_base  = WC_EMAIL_CUSTOMER_WORKSHOP_REMINDER_ONE_DAY_PATH .'../templates/';

        parent::__construct();
    }

    // Pas de trigger sur les statuts de commande: l'email est déclenché par un Cron job qui vérifie le meta "imminence" de chaque atelier chaque jour
}