<?PHP
class Filtre_Trading {

  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_form_filtre_trading() {
  ?>

    <form id='form_FILTRE_TRADE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='get'> 
      <label for='dat_deb'>Date&nbsp;Début</label>&nbsp;
      <input id='dat_deb' name='dat_deb' type='text' size='10' maxlength='10' value='' >&nbsp;&nbsp;
      <label for='dat_fin'>Date&nbsp;Fin</label>&nbsp;
      <input id='dat_fin' name='dat_fin' type='text' size='10' maxlength='10' value='' >&nbsp;&nbsp;
      <label for='strategie'>Stratégie</label>&nbsp;
      <?PHP Gerer_Strategie::afficher_selecteur_strategie(); ?>
      <label for='liste'>Liste des Trades</label>&nbsp;
      <input id='liste'   name='liste' type='checkbox' >&nbsp;&nbsp;
      <input type='button' id='filtrer' value='Filtrer' >
    </form>

    <div id='filtre_trade'></div>

  <?PHP
  }


}
?>