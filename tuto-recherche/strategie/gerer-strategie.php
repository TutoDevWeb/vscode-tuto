<?PHP
class Gerer_Strategie {

  protected static $connexion;

  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_saisir_form_STRATEGIE_TRADE() {
  ?>
    <form id='form_STRATEGIE_TRADE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='post'> 
      <table class='saisie'>
      <tr><th id='header' colspan='2'>CREER UNE STRATEGIE</th></tr>
      <tr>
        <td><label for='nom'>Nom de la stratégie</label></td>
        <td><input id='nom' name='nom' type='text' size='<?PHP echo PLZ ?>' maxlength='<?PHP echo PLM ?>' /></td>
      </tr>
      <tr>
        <td><label for='scenario'>Scénario</label></td>
        <td><textarea id='scenario' name='scenario' rows='<?PHP echo PSCR ?>' cols='<?PHP echo PSCC ?>'></textarea></td>
      </tr>
      <tr>
        <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
        <td class='td-bt-label-right'>
            <input type='hidden' name='etat_strategie' value='EN_COURS' />
            <input type='hidden' name='action' value='traiter_saisir_form_STRATEGIE_TRADE' />
            <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
        </td>
      </tr>
    </table>
  </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_saisir_form_STRATEGIE_TRADE($nom,$scenario) {

    $idu=$_SESSION['idu'];

    if(!get_magic_quotes_gpc()) {
      $nom         = mysqli_real_escape_string(self::$connexion,$nom);
      $scenario    = mysqli_real_escape_string(self::$connexion,$scenario);
    }

    if ( $scenario != '' && $nom != '' ) {

      $query = "INSERT INTO V6_liste_strategies(dat_deb,nom,scenario,idu,etat_strategie)
                VALUES (now(),'$nom','$scenario','$idu','EN_COURS')";
    
      if ( dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0) === true ) {
        echo "<div class='info'>L'opération a été crée avec succés</div>";   
        return(mysqli_insert_id(self::$connexion)); 
      } else {
        echo "<div class='info'>Echec au moment de la création de l'opération</div>";   
        return false;
      }
    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_modifier_form_STRATEGIE_TRADE($id_strategie) {

    $idu=$_SESSION['idu'];

    $query = "SELECT DATE(dat_deb),nom,scenario,etat_strategie FROM V6_liste_strategies WHERE idu='$idu' AND id_strategie='$id_strategie'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list($dat_deb,$nom,$scenario,$etat_strategie) =  mysqli_fetch_row($result);
    }

  ?>
    <form id='form_STRATEGIE_TRADE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='get'> 
      <table class='saisie'>
      <tr><th id='header' colspan='2'>MODIFIER STRATEGIE</th></tr>
      <tr>
        <td><label for='dat_ope'>Date</label></td>
        <td><input id='dat_deb' name='dat_deb' type='text' size='10' maxlength='10' value='<?PHP echo $dat_deb ?>' readonly />&nbsp;AAAA&#8209;MM&#8209;JJ</td>
      </tr>
      <tr>
        <td><label for='nom'>Nom de la Strat&eacute;gie</label></td>
        <td><input id='nom' name='nom' type='text' size='<?PHP echo PLZ ?>' maxlength='<?PHP echo PLM ?>' value='<?PHP echo $nom ?>'/></td>
      </tr>
      <tr>
        <td><label for='scenario'>Scénario</label></td>
        <td><textarea id='scenario' name='scenario' rows='<?PHP echo PSCR ?>' cols='<?PHP echo PSCC ?>'><?PHP echo $scenario ?></textarea></td>
      </tr>

      <tr>
        <td><label for='etat_strategie'>Etat Stratégie</label></td>
        <td>
        <select class='etat_strategie' id='etat_strategie' name='etat_strategie'>
        <?PHP 
        if ( $etat_strategie == 'EN_COURS' ) echo"<option value='EN_COURS' selected='selected'>En Cours</option>\n";
        else echo"<option value='EN_COURS'>En Cours</option>\n";  

        if ( $etat_strategie == 'TERMINER' ) echo"<option value='TERMINER' selected='selected'>Terminer</option>\n";
        else echo"<option value='TERMINER'>Terminer</option>\n";  

        if ( $etat_strategie == 'ARCHIVER' ) echo"<option value='ARCHIVER' selected='selected'>Archiver</option>\n";
        else echo"<option value='ARCHIVER'>Archiver</option>\n";  
        ?>
        </select>
        </td>
      </tr>
      <tr>
        <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
        <td class='td-bt-label-right'>
            <input type='hidden' name='id_strategie' value='<?PHP echo $id_strategie ?>' />
            <input type='hidden' name='action' value='traiter_modifier_form_STRATEGIE_TRADE' />
            <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
        </td>
      </tr>
    </table>
  </form>
  <?PHP
  }
  
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_modifier_form_STRATEGIE_TRADE($nom,$scenario,$id_strategie,$etat_strategie) {

    $idu=$_SESSION['idu'];

    if(!get_magic_quotes_gpc()) {
      $nom            = mysqli_real_escape_string(self::$connexion,$nom);
      $scenario       = mysqli_real_escape_string(self::$connexion,$scenario);
      $id_strategie   = mysqli_real_escape_string(self::$connexion,$id_strategie);
      $etat_strategie = mysqli_real_escape_string(self::$connexion,$etat_strategie);
    }

    if ( $nom != '' && $scenario != '' && $id_strategie != '' ) {

      if ( $etat_strategie == 'EN_COURS' )
    
        $query = "UPDATE V6_liste_strategies SET nom='$nom',scenario='$scenario',etat_strategie='$etat_strategie' WHERE id_strategie='$id_strategie' AND idu='$idu' LIMIT 1";

      else 

        $query = "UPDATE V6_liste_strategies SET nom='$nom',scenario='$scenario',etat_strategie='$etat_strategie',dat_fin=DATE(now()) WHERE id_strategie='$id_strategie' AND idu='$idu' LIMIT 1";

      dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);

    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   
  }  
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function get_liste_strategies($etat_strategie) {

    $idu=$_SESSION['idu'];

    $query = "SELECT id_strategie,nom FROM V6_liste_strategies WHERE idu='$idu' AND etat_strategie='$etat_strategie' ORDER BY nom ASC";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    

    $tabs_strategie = array();
    if ( mysqli_num_rows($result) ) {
      while ( list($id_strategie,$nom) = mysqli_fetch_row($result) ) {
        $tabs_strategie[$id_strategie]=$nom;
      }
    }
    return($tabs_strategie);

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function gerer_CONFIGURATION_STRATEGIE() {

    $idu=$_SESSION['idu'];

    $query = "SELECT id_strategie,dat_deb,dat_fin,nom,etat_strategie,scenario FROM V6_liste_strategies WHERE idu='$idu' ORDER BY dat_deb DESC";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      echo "<table class='data'>\n";
      echo "<tr><th>&nbsp;</th><th>Date&nbsp;D&eacute;but</th><th>Date&nbsp;Fin</th><th>Libell&eacute;</th><th>Scénario</th><th>Etat&nbsp;&nbsp;</th><th>&nbsp;</th><th style='text-align:center; width:60px;'><a class='bt-icon-plusthick' href='index-gestion-strategies.php?action=creer_strategie' title='Creer'>&nbsp;</a></th></tr>\n";
      while ( list($id_strategie,$dat_deb,$dat_fin,$nom,$etat_strategie,$scenario) =  mysqli_fetch_row($result) ) {
        echo "<tr>\n";
        echo "<td>$id_strategie</td>\n"; 
        $my_date=Util::format_date($dat_deb);
        echo "<td>$my_date</td>\n";
        $my_date=Util::format_date($dat_fin);
        echo "<td>$my_date</td>\n";
        echo "<td>$nom</td>\n";
        $PSCR=PSCR; $PSCC=PSCC;
        echo "<td><textarea class='scenario' name='scenario' rows='1' cols='$PSCC' readonly>$scenario</textarea></td>\n";
         
        echo "<td>$etat_strategie</td>\n";

        echo "<td style='text-align:center; width:60px;'><a class='bt-icon-pencil' href='index-gestion-strategies.php?action=modifier_strategie&id_strategie=$id_strategie'  title='Modifier'>&nbsp;</a></td>\n";        
        echo "<td style='text-align:center; width:60px;'><a class='bt-icon-trash' href=\"javascript:confirm_delete('index-gestion-strategies.php?action=supprimer_STRATEGIE_TRADE&id_strategie=$id_strategie')\" title='Supprimer'>&nbsp;</a></td>\n";

        echo "</tr>\n";
      }
      echo "</table>\n";
    }
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_form_SUPPRIMER_STRATEGIE_TRADE() {
  ?>
    <form id='supprimer_form_STRATEGIE_TRADE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='get'> 
      <table class='saisie'>
      <tr><th id='header' colspan='2'>SUPPRIMER STRATEGIE</th></tr>
      <tr>
        <td><label for='dat_ope'>Strat&eacute;gie</label></td>
        <td><?PHP $this->afficher_selecteur_strategie(); ?></td>
      </tr>
      <tr>
        <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
        <td class='td-bt-label-right'>
            <input type='hidden' name='action' value='supprimer_STRATEGIE_TRADE' />
            <button class='bt-label-disk' type='submit' value="Enregistrer"/>Supprimer</button>                        
        </td>
      </tr>
    </table>
  </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function afficher_selecteur_strategie_trading($id_strategie_selected='') {

    $idu=$_SESSION['idu'];

    $query = "SELECT id_strategie,nom FROM V6_liste_strategies WHERE idu='$idu' ORDER BY nom ASC";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    echo"<select id='id_strategie' name='id_strategie'>\n";
    echo "<option value='0'>Toutes</option>\n"; 
    if ( mysqli_num_rows($result) ) {
      while ( list($id_strategie,$nom) =  mysqli_fetch_row($result) ) {
        if ($id_strategie_selected == $id_strategie ) echo"<option value='$id_strategie' selected='selected'>$nom</option>\n";
        else echo"<option value='$id_strategie'>$nom</option>\n";
      }
    }
    echo"</select>\n";
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function get_nom_strategie_trading($id_strategie) {

    $query = "SELECT nom FROM V6_liste_strategies WHERE id_strategie='$id_strategie'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list($nom) =  mysqli_fetch_row($result);
      return($nom);     
    } return('Aucun');
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function supprimer_strategie($id_strategie) {

    $idu=$_SESSION['idu'];

    $query = "DELETE FROM V6_liste_strategies WHERE id_strategie='$id_strategie' AND idu='$idu' LIMIT 1";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  function __construct($connexion) {
	self::$connexion=$connexion;
  }
  
}
?>