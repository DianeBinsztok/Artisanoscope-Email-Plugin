<?php

class WC_Email_Customer_Workshop_Feedback extends WC_Email{

    /**
	 * Constructeur
	 */
    public function __construct() {

        // Afficher la classe custom dans l'espace admin

        // Slug de l'email: utilisable pour filtrer les autres données
        $this->id          = 'wc_email_customer_workshop_feedback';
        $this->title       = __( "Feedback d'atelier", "woocommerce" );
        $this->description = __( "Email envoyé l'atelier pour récolter des avis", "woocommerce");
        $this->customer_email = true;
        $this->heading     = __( "Comment s'est passé votre atelier?", "woocommerce" );
        // Traducteurs: la variable {blogname} est un placeholder
        $this->subject     = sprintf( _x( "[%s] Comment s'est passé votre atelier?", "Laissez un avis sur votre atelier avec l'Artisanoscope", "woocommerce" ), "{blogname}");
        // Chemins vers le template
        $this->template_html  = "../templates/workshop-feedback-email-template.php";
        $this->template_plain = "../templates/workshop-feedback-email-template.php";
        $this->template_base  = WC_EMAIL_CUSTOMER_WORKSHOP_FEEDBACK .'../templates/';

        parent::__construct();
    }

    // Pas de trigger sur les statuts de commande: l'email est déclenché par un Cron job qui vérifie le meta "imminence" de chaque atelier chaque jour
}