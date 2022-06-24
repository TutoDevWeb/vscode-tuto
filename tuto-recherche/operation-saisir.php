<?PHP
class Operation_Saisir {

  protected static $connexion;

  private  static $dat_ope;
  private  static $tim_ope;

  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_saisir_form_ACHAT_VENTE($idp,$type) {
    ?>
    <form id='form_ACHAT_VENTE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='get'> 
      <table class='saisie'>
      <tr><th id='header' colspan='2'><?PHP echo $type ?> de Titres</th></tr>
      <tr>
        <td><label for='dat_ope'>Date</label></td>
        <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo self::$dat_ope ?>'/>&nbsp;AAAA&#8209;MM&#8209;JJ</td>
      </tr>
      <tr>
        <td><label for='tim_ope'>Heure</label></td>
        <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='00:00:00' />&nbsp;HH:MM:SS</td>
      </tr>
      <tr>
        <td><label for='modr'>Mode de règlement</label></td>
        <td><input type='radio' name='modr' value='COMPTANT' checked />Comptant&nbsp;&nbsp;
            <input type='radio' name='modr' value='DIFFERE' />Différé
        </td>
      </tr>
      <tr>
        <td><label for='idv'>Titre</label></td>
        <td><input type='text' id='tags' size='<?PHP echo ZNVZ ?>' maxlength='<?PHP echo ZNVM ?>'/>&nbsp;&nbsp;<span id='note_erreur'></span>
            <input type='hidden' id='idv' name='idv' value='' />
        </td>
      </tr>
      <tr>
        <td><label for='nb'>Nombre</label></td>
        <td><input id='nb' name='nb' type='text' size='<?PHP echo NBZ ?>' maxlength='<?PHP echo NBM ?>' /></td>
      </tr>
      <tr>
        <td><label for='cours'>Cours</label></td>
        <td><input id='cours' name='cours' type='text' size='<?PHP echo COZ ?>' maxlength='<?PHP echo COM ?>' /></td>
      </tr>
      <tr>
        <td><label for='courtage'>Courtage</label></td>
        <td><input id='courtage' name='courtage' type='text' size='<?PHP echo CTAZ ?>' maxlength='<?PHP echo CTAM ?>' /></td>
      </tr>
      <tr>
        <td><label for='tva'>Tva</label></td>
        <td><input id='tva' name='tva' type='text' size='<?PHP echo TVAZ ?>' maxlength='<?PHP echo TVAZ ?>' />
            <input id='check_courtage' name='check_courtage' type='checkbox' title='Mettre à zéro' checked='checked' />
        </td>
      </tr>
      <tr>
        <td><label for='tb'>Taxe&nbsp;Bancaire</label></td>
        <td><input id='tb' name='tb' type='text' size='<?PHP echo TBZ ?>' maxlength='<?PHP echo TBM ?>' />
            <input id='check_tb' name='check_tb' type='checkbox' title='Mettre à zéro' checked='checked' />
        </td>
      </tr>
      <tr>
        <?PHP
          if ( $type == 'ACHAT' ) {
            echo "<td><label for='debit'>Débit</label></td>\n";
            printf("<td><input id='debit' name='debit' type='text' size='%s' maxlength='%s' /></td>\n",ZDZ,ZDM);
          } else {
            echo "<td class='credit'><label for='credit'>Crédit</label></td>\n";
            printf("<td class='credit'><input id='credit' name='credit' type='text' size='%s' maxlength='%s' /></td>\n",ZCZ,ZCM);
          }
        ?>
      </tr>    
      <tr>
        <td><label for='com'>Commentaire</label></td>
        <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' /></td>
      </tr>
      <tr>
        <td><label for='http_link'>Lien</label></td>
        <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' /></td>
      </tr>
      <tr>
        <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
        <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
            <input type='hidden' id='type' name='type' value='<?PHP echo $type ?>' />
            <input type='hidden' name='action' value='traiter_saisir_form_ACHAT_VENTE' />
            <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
        </td>
      </tr>
    </table>
  </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_saisir_form_ACHAT_VENTE($dat_ope,$tim_ope,$idp,$type,$modr,$credit,$debit,$com,$idv,$nb,$cours,$courtage,$tva,$tb,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope      = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope      = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $idp          = mysqli_real_escape_string(self::$connexion,$idp);
      $type         = mysqli_real_escape_string(self::$connexion,$type);
      $modr         = mysqli_real_escape_string(self::$connexion,$modr);
      $credit       = mysqli_real_escape_string(self::$connexion,$credit);
      $debit        = mysqli_real_escape_string(self::$connexion,$debit);
      $com          = mysqli_real_escape_string(self::$connexion,$com);
      $idv          = mysqli_real_escape_string(self::$connexion,$idv);
      $nb           = mysqli_real_escape_string(self::$connexion,$nb);
      $cours        = mysqli_real_escape_string(self::$connexion,$cours);
      $courtage     = mysqli_real_escape_string(self::$connexion,$courtage);
      $tva          = mysqli_real_escape_string(self::$connexion,$tva);
      $tb           = mysqli_real_escape_string(self::$connexion,$tb);
      $http_link    = mysqli_real_escape_string(self::$connexion,$http_link);

    }

    /*    
    echo "<p>dat_ope => $dat_ope</p>";
    echo "<p>idp => $idp</p>";
    echo "<p>type => $type</p>";
    echo "<p>idv => $idv</p>";
    echo "<p>nb => $nb</p>";
    echo "<p>cours => $cours</p>";
    echo "<p>debit => $debit</p>";
    echo "<p>credit => $credit</p>";
    echo "<p>tb => $tb</p>";
    */
    
  
    if ( $dat_ope != '' && $idp != '' && $type != '' && $modr != '' && ( $credit !=0 || $debit !=0) && $idv!=0 && $nb!=0 && $cours != 0 ) {

      $idu=$_SESSION['idu'];

      if ( $tb == '' ) $tb = 0.0;

      $datime_ope=$dat_ope.' '.$tim_ope;    
      $query = "INSERT INTO V6_historique_operations(datime_ope,idp,type,modr,credit,debit,com,
                                               idv,nb,cours,courtage,tva,tb,http_link,need_update,idu) 
                VALUES ('$datime_ope','$idp','$type','$modr','$credit','$debit','$com',
                        '$idv','$nb','$cours','$courtage','$tva','$tb','$http_link','1','$idu')";
    
      if ( dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0) === true ) {
        echo "<div class='info'>L'opération a été crée avec succès</div>";   
        return(mysqli_insert_id(self::$connexion)); 
      } else {
        echo "<div class='info'>DTB :: Echec création opération</div>";   
        return false;
      }
    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_saisir_form_LIQUIDITE($idp,$type) {
    ?>
    <form id='form_LIQUIDITE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='get'> 
      <table class='saisie'>
      <tr>
        <th id='header' colspan='2'>
        <?PHP 
        if     ( $type == 'APPORT_LIQUIDITE' ) echo "Versement de liquidité";
        elseif ( $type == 'RETRAIT_LIQUIDITE') echo "Retrait de liquidité";
        ?>
        </th>
      </tr>
      <tr>
        <td><label for='dat_ope'>Date</label></td>
        <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo self::$dat_ope ?>' />&nbsp;AAAA&#8209;MM&#8209;JJ</td>
      </tr>
      <tr>
        <td><label for='tim_ope'>Heure</label></td>
        <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo self::$tim_ope ?>' />&nbsp;HH:MM:SS</td>      
      </tr>
      <tr>
        <?PHP 
        if ( $type == 'RETRAIT_LIQUIDITE' ) { 
          echo "<td><label for='debit'>Montant&nbsp;du&nbsp;retrait</label></td>\n";
          printf("<td><input id='debit' name='debit' type='text' size='%s' maxlength='%s' /></td>\n",ZDZ,ZDM);
        }elseif ( $type == 'APPORT_LIQUIDITE') {
          echo "<td><label for='credit'>Montant&nbsp;du&nbsp;versement</label></td>\n";
          printf("<td><input id='credit' name='credit' type='text' size='%s' maxlength='%s' /></td>\n",ZCZ,ZCM);
        }
        ?>
      </tr>      
      <tr>
        <td><label for='com'>Commentaire</label></td>
        <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' /></td>
      </tr>
      <tr>
        <td><label for='http_link'>Lien</label></td>
        <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' /></td>
      </tr>
      <tr>
        <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
        <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
            <input type='hidden' id='type' name='type' value='<?PHP echo $type ?>' />
            <input type='hidden' name='action' value='traiter_saisir_form_LIQUIDITE' />
            <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
        </td>
      </tr>
    </table
  ></form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_saisir_form_LIQUIDITE($dat_ope,$tim_ope,$idp,$type,$credit,$debit,$com,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope     = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope     = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $idp         = mysqli_real_escape_string(self::$connexion,$idp);
      $type        = mysqli_real_escape_string(self::$connexion,$type);
      $credit      = mysqli_real_escape_string(self::$connexion,$credit);
      $debit       = mysqli_real_escape_string(self::$connexion,$debit);
      $com         = mysqli_real_escape_string(self::$connexion,$com);
      $http_link   = mysqli_real_escape_string(self::$connexion,$http_link);
    }

    if ( $dat_ope != '' && $idp != '' && $type != '' && ( $credit !=0 || $debit !=0) ) {

      $idu=$_SESSION['idu'];

      $datime_ope=$dat_ope.' '.$tim_ope;    
      $query = "INSERT INTO V6_historique_operations(datime_ope,idp,type,credit,debit,com,http_link,need_update,idu) 
                VALUES ('$datime_ope','$idp','$type','$credit','$debit','$com','$http_link','1','$idu')";
    
      if ( dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0) === true ) {
        echo "<div class='info'>L'opération a été crée avec succès</div>";   
        return(mysqli_insert_id(self::$connexion)); 
      } else {
        echo "<div class='info'>DTB :: Echec création opération</div>";   
        return false;
      }
    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_saisir_form_DIVIDENDE($idp,$type) {
    ?>
    <form id='form_DIVIDENDE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='get'> 
      <table class='saisie'>
        <tr><th id='header' colspan='2'>Paiement de Dividende</th></tr>
        <tr>
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo self::$dat_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo self::$tim_ope ?>' /></td>
        </tr>      
        <tr>
          <td><label for='credit'>Montant&nbsp;du&nbsp;dividende</label></td>
          <td><input id='credit' name='credit' type='text' size='<?PHP echo ZCZ ?>' maxlength='<?PHP echo ZCM ?>' /></td>
        </tr>
        <tr>
          <td><label for='nb'>Nombre</label></td>
          <td><input id='nb' name='nb' type='text' size='<?PHP echo NBZ ?>' maxlength='<?PHP echo NBM ?>' /></td>
        </tr>
        <tr>
          <td><label for='idv'>Titre</label></td>
          <td><input type='text' id='tags' size='<?PHP echo ZNVZ ?>' maxlength='<?PHP echo ZNVM ?>'/>&nbsp;&nbsp;<span id='note_erreur'></span>
              <input type='hidden' id='idv' name='idv' value='' />
          </td>
        </tr>
        <tr>
          <td><label for='com'>Commentaire</label></td>
          <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' /></td>
        </tr>
        <tr>
          <td><label for='http_link'>Lien</label></td>
          <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' /></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
            <input type='hidden' id='type' name='type' value='<?PHP echo $type ?>' />
            <input type='hidden' name='action' value='traiter_saisir_form_DIVIDENDE' />
            <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
          </td>
        </tr>
      </table>
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_saisir_form_DIVIDENDE($dat_ope,$tim_ope,$idp,$type,$credit,$nb,$idv,$com,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope     = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope     = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $idp         = mysqli_real_escape_string(self::$connexion,$idp);
      $type        = mysqli_real_escape_string(self::$connexion,$type);
      $credit      = mysqli_real_escape_string(self::$connexion,$credit);
      $nb          = mysqli_real_escape_string(self::$connexion,$nb);
      $idv         = mysqli_real_escape_string(self::$connexion,$idv);
      $com         = mysqli_real_escape_string(self::$connexion,$com);
      $http_link   = mysqli_real_escape_string(self::$connexion,$http_link);
    }

    if ( $dat_ope != '' && $idp != '' && $type != '' && $credit != 0 && $nb != 0 && $idv != 0 ) {

      $idu=$_SESSION['idu'];

      $datime_ope=$dat_ope.' '.$tim_ope;    
      $query = "INSERT INTO V6_historique_operations(datime_ope,idp,type,credit,nb,idv,com,http_link,need_update,idu) 
                VALUES ('$datime_ope','$idp','$type','$credit','$nb','$idv','$com','$http_link','1','$idu')";
    
      if ( dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0) === true ) {
        echo "<div class='info'>L'opération a été crée avec succès</div>";   
        return(mysqli_insert_id(self::$connexion)); 
      } else {
        echo "<div class='info'>Echec au moment de la création de l'opération</div>";   
        return false;
      }
    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_saisir_form_FRAIS($idp,$type) {
    ?>
    <form id='form_FRAIS' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='get'> 
      <table class='saisie'>
        <tr><th id='header' colspan='2'>Paiement de Frais</th></tr>
        <tr>
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo self::$dat_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo self::$tim_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='debit'>Montant&nbsp;des&nbsp;frais</label></td>
          <td><input id='debit' name='debit' type='text' size='<?PHP echo ZDZ ?>' maxlength='<?PHP echo ZDM ?>' /></td>
        </tr>
        <tr>
          <td><label for='com'>Commentaire</label></td>
          <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' /></td>
        </tr>
        <tr>
          <td><label for='http_link'>Lien</label></td>
          <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' /></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
            <input type='hidden' id='type' name='type' value='<?PHP echo $type ?>' />
            <input type='hidden' name='action' value='traiter_saisir_form_FRAIS' />
            <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
          </td>
        </tr>
      </table
    ></form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_saisir_form_FRAIS($dat_ope,$tim_ope,$idp,$type,$debit,$com,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope     = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope     = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $idp         = mysqli_real_escape_string(self::$connexion,$idp);
      $type        = mysqli_real_escape_string(self::$connexion,$type);
      $debit       = mysqli_real_escape_string(self::$connexion,$debit);
      $com         = mysqli_real_escape_string(self::$connexion,$com);
      $http_link   = mysqli_real_escape_string(self::$connexion,$http_link);
    }

    if ( $dat_ope != '' && $idp != '' && $type != '' && $debit != 0 ) {

      $idu=$_SESSION['idu'];
    
      $datime_ope=$dat_ope.' '.$tim_ope;    
      $query = "INSERT INTO V6_historique_operations(datime_ope,idp,type,debit,com,http_link,need_update,idu) 
                VALUES ('$datime_ope','$idp','$type','$debit','$com','$http_link','1','$idu')";
    
      if ( dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0) === true ) {
        echo "<div class='info'>L'opération a été crée avec succès</div>";   
        return(mysqli_insert_id(self::$connexion)); 
      } else {
        echo "<div class='info'>Echec au moment de la création de l'opération</div>";   
        return false;
      }
    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_saisir_form_ATTRIBUTION_GRATUITE($idp,$type) {
    ?>
    <form id='form_ATTRIBUTION_GRATUITE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='get'> 
      <table class='saisie'>
        <tr><th id='header' colspan='2'>Attribution d'actions gratuites</th></tr>
        <tr>
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo self::$dat_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8'  value='<?PHP echo self::$tim_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='idv'>Titre</label></td>
        <td><input type='text' id='tags' size='<?PHP echo ZNVZ ?>' maxlength='<?PHP echo ZNVM ?>'/>&nbsp;&nbsp;<span id='note_erreur'></span>
              <input type='hidden' id='idv' name='idv' value='' />
          </td>
        </tr>
        <tr>
          <td><label for='nb'>Nombre</label></td>
          <td><input id='nb' name='nb' type='text' size='<?PHP echo NBZ ?>' maxlength='<?PHP echo NBM ?>' /></td>
        </tr>
        <tr>
          <td><label for='cours'>Cours</label></td>
          <td><input id='cours' name='cours' type='text' size='<?PHP echo COZ ?>' maxlength='<?PHP echo COM ?>' /></td>
        </tr>
        <tr>
          <td><label for='com'>Commentaire</label></td>
          <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' /></td>
        </tr>
        <tr>
          <td><label for='http_link'>Lien</label></td>
          <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' /></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
            <input type='hidden' id='type' name='type' value='<?PHP echo $type ?>' />
            <input type='hidden' name='action' value='traiter_saisir_form_ATTRIBUTION_GRATUITE' />
            <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
          </td>
        </tr>
      </table>
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_saisir_form_ATTRIBUTION_GRATUITE($dat_ope,$tim_ope,$idp,$type,$com,$idv,$nb,$cours,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope     = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope     = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $idp         = mysqli_real_escape_string(self::$connexion,$idp);
      $type        = mysqli_real_escape_string(self::$connexion,$type);
      $com         = mysqli_real_escape_string(self::$connexion,$com);
      $idv         = mysqli_real_escape_string(self::$connexion,$idv);
      $nb          = mysqli_real_escape_string(self::$connexion,$nb);
      $cours       = mysqli_real_escape_string(self::$connexion,$cours);
      $http_link   = mysqli_real_escape_string(self::$connexion,$http_link);
    }


    if ( $dat_ope != '' && $idp != '' && $type != '' && $idv != 0 && $nb != 0 ) {

      $idu=$_SESSION['idu'];
    
      $datime_ope=$dat_ope.' '.$tim_ope;    
      $query = "INSERT INTO V6_historique_operations(datime_ope,idp,type,com,idv,nb,cours,http_link,need_update,idu)
                VALUES ('$datime_ope','$idp','$type','$com','$idv','$nb','$cours','$http_link','1','$idu')";
    
      if ( dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0) === true ) {
        echo "<div class='info'>L'opération a été crée avec succès</div>";   
        return(mysqli_insert_id(self::$connexion)); 
      } else {
        echo "<div class='info'>Echec au moment de la création de l'opération</div>";   
        return false;
      }
    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_saisir_form_CRD($idp) {
    ?>
    <form id='form_CRD' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='get'> 
      <table class='saisie'>
        <tr><th id='header' colspan='2'>Commission de règlement différé</th></tr>
        <tr>
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo self::$dat_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8'  value='<?PHP echo self::$tim_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='debit'>Commission de règlement différé</label></td>
          <td><input id='debit' name='debit' type='text' size='<?PHP echo ZDZ ?>' maxlength='<?PHP echo ZDM ?>' /></td>
        </tr>
        <tr>
          <td><label for='com'>Commentaire</label></td>
          <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' /></td>
        </tr>
        <tr>
          <td><label for='http_link'>Lien</label></td>
          <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' /></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
            <input type='hidden' id='type' name='type' value='CRD' />
            <input type='hidden' name='action' value='traiter_saisir_form_CRD' />
            <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
          </td>
        </tr>
      </table>
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_saisir_form_CRD($dat_ope,$tim_ope,$idp,$debit,$com,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope     = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope     = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $idp         = mysqli_real_escape_string(self::$connexion,$idp);
      $debit       = mysqli_real_escape_string(self::$connexion,$debit);
      $com         = mysqli_real_escape_string(self::$connexion,$com);
      $http_link   = mysqli_real_escape_string(self::$connexion,$http_link);
    }

    if ( $dat_ope != '' && $idp != '' && $debit != 0 ) {

      $idu=$_SESSION['idu'];
    
      $datime_ope=$dat_ope.' '.$tim_ope;    
      $query = "INSERT INTO V6_historique_operations(datime_ope,idp,type,debit,com,http_link,need_update,idu) 
                VALUES ('$datime_ope','$idp','CRD','$debit','$com','$http_link','1','$idu')";
    
      if ( dtbi_query(self::$connexion,$query,__FILE__,__LINE__,1) === true ) {
        echo "<div class='info'>L'opération a été crée avec succès</div>";   
        return(mysqli_insert_id(self::$connexion)); 
      } else {
        echo "<div class='info'>Echec au moment de la création de l'opération</div>";   
        return false;
      }
    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }  
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_saisir_form_ORDRE_ACHAT_VENTE($idp,$type) {
    ?>
    <form id='form_ORDRE_ACHAT_VENTE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='get'> 
      <table class='saisie'>
      <tr><th id='header' colspan='2'><?PHP echo $type ?> de Titres</th></tr>
      <tr>
        <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
        <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo self::$dat_ope ?>' /></td>
      </tr>
      <tr>
        <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
        <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo self::$tim_ope ?>' /></td>
      </tr>
      <tr>
        <td><label for='id_strategie'>Stratégie de Trading</label></td>
        <td>
            <?PHP Gerer_Strategie::afficher_selecteur_strategie($id_strategie_selected=''); ?>
        </td>
      </tr>
      <tr>
        <td><label for='idv'>Titre</label></td>
        <td><input type='text' id='tags' size='<?PHP echo ZNVZ ?>' maxlength='<?PHP echo ZNVM ?>'/>&nbsp;&nbsp;<span id='note_erreur'></span>
            <input type='hidden' id='idv' name='idv' value='' />
        </td>
      </tr>
      <tr>
        <td><label for='nb'>Nombre</label></td>
        <td><input id='nb' name='nb' type='text' size='<?PHP echo NBZ ?>' maxlength='<?PHP echo NBM ?>' /></td>
      </tr>
      <tr>
        <td><label for='cours'>Cours</label></td>
        <td><input id='cours' name='cours' type='text' size='<?PHP echo COZ ?>' maxlength='<?PHP echo COM ?>' /></td>
      </tr>
      <tr>
        <td><label for='courtage'>Courtage</label></td>
        <td><input id='courtage' name='courtage' type='text' size='<?PHP echo CTAZ ?>' maxlength='<?PHP echo CTAM ?>' /></td>
      </tr>
      <tr>
        <td><label for='tva'>Tva</label></td>
        <td><input id='tva' name='tva' type='text' size='<?PHP echo TVAZ ?>' maxlength='<?PHP echo TVAZ ?>' />
            <input id='check_courtage' name='check_courtage' type='checkbox' title='Mettre à zéro' checked='checked' />
        </td>
      </tr>
      <tr>
        <td><label for='tb'>Taxe&nbsp;Bancaire</label></td>
        <td><input id='tb' name='tb' type='text' size='<?PHP echo TBZ ?>' maxlength='<?PHP echo TBM ?>' />
            <input id='check_tb' name='check_tb' type='checkbox' title='Mettre à zéro' checked='checked' />
        </td>
      </tr>
      <tr>
        <?PHP 
        if ( $type == 'ORDRE_ACHAT' ) { 
          echo "<td><label for='debit'>Investissement</label></td>\n";
          printf("<td><input id='debit' name='debit' type='text' size='%s' maxlength='%s' /></td>\n",ZDZ,ZDM);
        }elseif ( $type == 'ORDRE_VENTE') {
          echo "<td><label for='credit'>Crédit dégagé</label></td>\n";
          printf("<td><input id='credit' name='credit' type='text' size='%s' maxlength='%s' /></td>\n",ZCZ,ZCM);
        }
        ?>
      </tr>    
      <tr>
        <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
        <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
            <input type='hidden' id='type' name='type' value='<?PHP echo $type ?>' />
            <input type='hidden' name='action' value='traiter_saisir_form_ORDRE_ACHAT_VENTE' />
            <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
        </td>
      </tr>
    </table>
  </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_saisir_form_ORDRE_ACHAT_VENTE($dat_ope,$tim_ope,$idp,$type,$credit,$debit,$idv,$nb,$cours,$courtage,$tva,$tb,$id_strategie) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope      = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope      = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $idp          = mysqli_real_escape_string(self::$connexion,$idp);
      $type         = mysqli_real_escape_string(self::$connexion,$type);
      $credit       = mysqli_real_escape_string(self::$connexion,$credit);
      $debit        = mysqli_real_escape_string(self::$connexion,$debit);
      $idv          = mysqli_real_escape_string(self::$connexion,$idv);
      $nb           = mysqli_real_escape_string(self::$connexion,$nb);
      $cours        = mysqli_real_escape_string(self::$connexion,$cours);
      $courtage     = mysqli_real_escape_string(self::$connexion,$courtage);
      $tva          = mysqli_real_escape_string(self::$connexion,$tva);
      $tb           = mysqli_real_escape_string(self::$connexion,$tb);
      $id_strategie = mysqli_real_escape_string(self::$connexion,$id_strategie);
    }

/*
echo "dat_ope => $dat_ope<br/>";
echo "idp     => $idp<br/>";
echo "type    => $type<br/>";
echo "credit  => $credit<br/>";
echo "debit   => $debit<br/>";
echo "idv     => $idv<br/>";
echo "nb      => $nb<br/>";
echo "cours   => $cours<br/>";
echo "id_plan => $id_plan<br/>";
*/

    if ( $dat_ope != '' && $idp != '' && $type != '' && ( $credit !=0 || $debit !=0) && $idv!=0 && $nb!=0 && $cours != 0 && $id_strategie != 0 ) {

      $idu=$_SESSION['idu'];

      $datime_ope=$dat_ope.' '.$tim_ope;    
      $query = "INSERT INTO V6_historique_operations(datime_ope,idp,type,pcredit,pdebit,idv,nb,cours,courtage,tva,tb,id_strategie,idu) 
                VALUES ('$datime_ope','$idp','$type','$credit','$debit','$idv','$nb','$cours','$courtage','$tva','$tb','$id_strategie','$idu')";
    
      if ( dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0) === true ) {
        echo "<div class='info'>L'ordre a été crée avec succès</div>";   
        return(mysqli_insert_id(self::$connexion)); 
      } else {
        echo "<div class='info'>DTB :: Echec création de l'ordre</div>";   
        return false;
      }
    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  function __construct($connexion) {
  
    self::$connexion=$connexion;

    $query = "SELECT CURDATE(),CURTIME()";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list(self::$dat_ope,self::$tim_ope) =  mysqli_fetch_row($result);
    }

  }
  
}
?>