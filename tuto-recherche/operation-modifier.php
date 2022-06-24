<?PHP
class Operation_Modifier {

  protected static $connexion;

  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_modifier_form_ACHAT_VENTE($ido) {

    $query = "SELECT DATE(datime_ope),TIME(datime_ope),idp,type,modr,sens,credit,debit,com,idv,nb,cours,courtage,tva,tb,http_link FROM V6_historique_operations WHERE ido='$ido'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list($dat_ope,$tim_ope,$idp,$type,$modr,$sens,$credit,$debit,$com,$idv,$nb,$cours,$courtage,$tva,$tb,$http_link) =  mysqli_fetch_row($result);
    }
    ?>
    <form id='form_ACHAT_VENTE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='post'> 
      <table class='saisie'>
        <tr><th id='header' colspan='2'><?PHP echo $type ?> de Titres</th></tr>
        <tr>
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo $dat_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo $tim_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='modr'>Mode de règlement</label></td>
          <td><input type='radio' name='modr' value='COMPTANT' <?PHP if ( $modr == 'COMPTANT' ) echo 'checked' ?> />Comptant&nbsp;&nbsp;
              <input type='radio' name='modr' value='DIFFERE'  <?PHP if ( $modr == 'DIFFERE'  ) echo 'checked' ?> />Différé
          </td>
        </tr>
        <tr>
          <td><label for='idv'>Valeur</label></td>
          <td>
             <input type='text' id='tags' size='30' value='<?PHP echo Cotations::get_val_name($idv); ?>' readonly />
             <input type='hidden' id='idv' name='idv' value='<?PHP echo $idv ?>' />
          </td>
        </tr>
        <tr>
          <td><label for='nb'>Nombre</label></td>
          <td><input id='nb' name='nb' type='text' size='<?PHP echo NBZ ?>' maxlength='<?PHP echo NBM ?>' value='<?PHP echo $nb ?>'/></td>
        </tr>
        <tr>
          <td><label for='cours'>Cours</label></td>
          <td><input id='cours' name='cours' type='text' size='<?PHP echo COZ ?>' maxlength='<?PHP echo COM ?>' value='<?PHP echo $cours ?>'/></td>
        </tr>
        <tr>
          <td><label for='courtage'>Courtage</label></td>
          <td><input id='courtage' name='courtage' type='text' size='<?PHP echo CTAZ ?>' maxlength='<?PHP echo CTAM ?>' value='<?PHP echo $courtage ?>'/></td>
        </tr>
        <tr>
          <td><label for='tva'>Tva</label></td>
          <td><input id='tva' name='tva' type='text' size='<?PHP echo TVAZ ?>' maxlength='<?PHP echo TVAZ ?>' value='<?PHP echo $tva ?>'/>
              <input id='check_courtage' name='check_courtage' type='checkbox' title='Mettre à zéro' checked='checked' />
          </td>
        </tr>
        <tr>
          <td><label for='tb'>Taxe&nbsp;Bancaire</label></td>
          <td><input id='tb' name='tb' type='text' size='<?PHP echo TBZ ?>' maxlength='<?PHP echo TBM ?>' value='<?PHP echo $tb ?>'/>
              <input id='check_tb' name='check_tb' type='checkbox' title='Mettre à zéro' checked='checked' />
          </td>
        </tr>
        <tr>
          <?PHP 
          if ( $type == 'ACHAT' ) { 
            echo "<td><label for='debit'>Montant du débit</label></td>\n";
            printf("<td><input id='debit' name='debit' type='text' size='%s' maxlength='%s' value='%s' /></td>\n",ZDZ,ZDM,$debit);
          }elseif ( $type == 'VENTE') {
            echo "<td><label for='credit'>Montant du crédit</label></td>\n";
            printf("<td><input id='credit' name='credit' type='text' size='%s' maxlength='%s' value='%s' /></td>\n",ZCZ,ZCM,$credit);
          }
          ?>
        </tr>
        <tr>
          <td><label for='com'>Commentaire</label></td>
          <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' value='<?PHP echo $com ?>' /></td>
        </tr>
        <tr>
          <td><label for='http_link'>Lien</label></td>
          <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' value='<?PHP echo $http_link ?>'/></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
                                        <input type='hidden' name='ido' value='<?PHP echo $ido ?>' />
                                        <input type='hidden' id='type' name='type' value='<?PHP echo $type ?>' />
                                        <input type='hidden' name='action' value='traiter_modifier_form_ACHAT_VENTE' />
                                        <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
          </td>
        </tr>
      </table>
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_modifier_form_ACHAT_VENTE($dat_ope,$tim_ope,$ido,$type,$modr,$credit,$debit,$com,$idv,$nb,$cours,$courtage,$tva,$tb,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope      = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope      = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $ido          = mysqli_real_escape_string(self::$connexion,$ido);
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

    if ( $dat_ope != '' && $ido != '' && ( $credit !=0 || $debit !=0) && $idv!=0 && $nb!=0 && $cours != 0 ) {

      $idu=$_SESSION['idu'];

      $datime_ope=$dat_ope.' '.$tim_ope;
      $query = "UPDATE V6_historique_operations SET datime_ope='$datime_ope',
                                                    modr='$modr',
                                                    credit='$credit',
                                                    debit='$debit',
                                                    com='$com',
                                                    idv='$idv',
                                                    nb='$nb',
                                                    cours='$cours',
                                                    courtage='$courtage',
                                                    tva='$tva',
                                                    tb='$tb',
                                                    http_link='$http_link',
                                                    need_update='1' 
                                                    WHERE idu='$idu' AND ido='$ido' LIMIT 1";    
      dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);

    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_modifier_form_LIQUIDITE($ido) {

    $query = "SELECT DATE(datime_ope),TIME(datime_ope),idp,type,credit,debit,com,http_link FROM V6_historique_operations WHERE ido='$ido'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list($dat_ope,$tim_ope,$idp,$type,$credit,$debit,$com,$http_link) =  mysqli_fetch_row($result);
    }

    ?>
    <form id='form_LIQUIDITE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='post'> 
      <table class='saisie'>
        <tr>
          <th id='header' colspan='2'>
               <?PHP if     ( $type == 'APPORT_LIQUIDITE' ) echo "Versement de liquidité";
                     elseif ( $type == 'RETRAIT_LIQUIDITE') echo "Retrait de liquidité";?>
          </th>
        </tr>
        <tr> 
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo $dat_ope ?>'/></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo $tim_ope ?>' /></td>      
        </tr>
        <tr>
          <?PHP if ( $type == 'RETRAIT_LIQUIDITE' ) { 
                      echo "<td><label for='debit'>Montant&nbsp;du&nbsp;retrait</label></td>\n";
                      printf("<td><input id='debit' name='debit' type='text' size='%s' maxlength='%s' value='%s' /></td>\n",ZDZ,ZDM,$debit);
                    }elseif ( $type == 'APPORT_LIQUIDITE') {
                      echo "<td><label for='credit'>Montant&nbsp;du&nbsp;versement</label></td>\n";
                      printf("<td><input id='credit' name='credit' type='text' size='%s' maxlength='%s' value='%s'/></td>\n",ZCZ,ZCM,$credit);
                    }
          ?>
        </tr>      
        <tr>
          <td><label for='com'>Commentaire</label></td>
          <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' value='<?PHP echo $com ?>' /></td>
        </tr>
        <tr>
          <td><label for='http_link'>Lien</label></td>
          <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' value='<?PHP echo $http_link ?>'/></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
                                        <input type='hidden' name='ido' value='<?PHP echo $ido ?>' />
                                        <input type='hidden' name='action' value='traiter_modifier_form_LIQUIDITE' />
                                        <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
          </td>
        </tr>
      </table>
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_modifier_form_LIQUIDITE($dat_ope,$tim_ope,$ido,$type,$credit,$debit,$com,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope     = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope     = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $ido         = mysqli_real_escape_string(self::$connexion,$ido);
      $type        = mysqli_real_escape_string(self::$connexion,$type);
      $credit      = mysqli_real_escape_string(self::$connexion,$credit);
      $debit       = mysqli_real_escape_string(self::$connexion,$debit);
      $com         = mysqli_real_escape_string(self::$connexion,$com);
      $http_link   = mysqli_real_escape_string(self::$connexion,$http_link);
    }

    if ( $dat_ope != '' && $ido != '' && ( $credit !=0 || $debit !=0) ) {

      $idu=$_SESSION['idu'];

      $datime_ope=$dat_ope.' '.$tim_ope;
      $query = "UPDATE V6_historique_operations SET datime_ope='$datime_ope',
                                                    credit='$credit',
                                                    debit='$debit',
                                                    com='$com',
                                                    http_link='$http_link', 
                                                    need_update='1' 
                                                    WHERE idu='$idu' AND ido='$ido' LIMIT 1";    
      dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);

    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   


  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_modifier_form_DIVIDENDE($ido) {

    $query = "SELECT DATE(datime_ope),TIME(datime_ope),idp,type,credit,nb,com,idv,http_link FROM V6_historique_operations WHERE ido='$ido'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list($dat_ope,$tim_ope,$idp,$type,$credit,$nb,$com,$idv,$http_link) =  mysqli_fetch_row($result);
    }
    
    ?>
    <form id='form_DIVIDENDE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='post'> 
      <table class='saisie'>
        <tr><th id='header' colspan='2'>Paiement de Dividende</th></tr>
        <tr>
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo $dat_ope ?>'/></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo $tim_ope ?>' /></td>
        </tr>
        <tr>      
          <td><label for='credit'>Montant&nbsp;du&nbsp;dividende</label></td>
          <td><input id='credit' name='credit' type='text' size='<?PHP echo ZCZ ?>' maxlength='<?PHP echo ZCM ?>' value='<?PHP echo $credit ?>' /></td>
        </tr>
        <tr>
          <td><label for='nb'>Nombre</label></td>
          <td><input id='nb' name='nb' type='text' size='<?PHP echo NBZ ?>' maxlength='<?PHP echo NBM ?>' value='<?PHP echo $nb ?>' /></td>
        </tr>
        <tr>
          <td><label for='idv'>Valeur</label></td>
          <td><?PHP self::afficher_selecteur_titre($idv); ?></td>
        </tr>
        <tr>
          <td><label for='com'>Commentaire</label></td>
          <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' value='<?PHP echo $com ?>' /></td>
        </tr>
        <tr>
          <td><label for='http_link'>Lien</label></td>
          <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' value='<?PHP echo $http_link ?>'/></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
                                        <input type='hidden' name='ido' value='<?PHP echo $ido ?>' />
                                        <input type='hidden' name='action' value='traiter_modifier_form_DIVIDENDE' />
                                        <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
          </td>
        </tr>
      </table>
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_modifier_form_DIVIDENDE($dat_ope,$tim_ope,$ido,$type,$credit,$nb,$idv,$com,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope     = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope     = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $ido         = mysqli_real_escape_string(self::$connexion,$ido);
      $type        = mysqli_real_escape_string(self::$connexion,$type);
      $credit      = mysqli_real_escape_string(self::$connexion,$credit);
      $nb          = mysqli_real_escape_string(self::$connexion,$nb);
      $idv         = mysqli_real_escape_string(self::$connexion,$idv);
      $com         = mysqli_real_escape_string(self::$connexion,$com);
      $http_link   = mysqli_real_escape_string(self::$connexion,$http_link);
    }

    if ( $dat_ope != '' && $ido != '' && $credit != 0 && $nb != 0 && $idv != 0 ) {

      $idu=$_SESSION['idu'];

      $datime_ope=$dat_ope.' '.$tim_ope;
      $query = "UPDATE V6_historique_operations SET datime_ope='$datime_ope',
                                             credit='$credit',
                                             nb='$nb',
                                             idv='$idv',
                                             com='$com',
                                             http_link='$http_link', 
                                             need_update='1' 
                                             WHERE idu='$idu' AND ido='$ido' LIMIT 1";    
      dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);

    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_modifier_form_FRAIS($ido) {

    $query = "SELECT DATE(datime_ope),TIME(datime_ope),idp,type,debit,com,http_link FROM V6_historique_operations WHERE ido='$ido'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list($dat_ope,$tim_ope,$idp,$type,$debit,$com,$http_link) =  mysqli_fetch_row($result);
    }

    ?>
    <form id='form_FRAIS' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='post'> 
      <table class='saisie'>
        <tr><th id='header' colspan='2'>Paiement de Frais</th></tr>
        <tr> 
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo $dat_ope ?>'/></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo $tim_ope ?>' /></td>
        </tr>      
        <tr>
          <td><label for='debit'>Montant&nbsp;des&nbsp;frais</label></td>
          <td><input id='debit' name='debit' type='text' size='<?PHP echo ZDZ ?>' maxlength='<?PHP echo ZDM ?>' value='<?PHP echo $debit ?>'/></td>
        </tr>
        <tr>
          <td><label for='com'>Commentaire</label></td>
          <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' value='<?PHP echo $com ?>' /></td>
        </tr>
        <tr>
          <td><label for='http_link'>Lien</label></td>
          <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' value='<?PHP echo $http_link ?>'/></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
                                        <input type='hidden' name='ido' value='<?PHP echo $ido ?>' />
                                        <input type='hidden' name='action' value='traiter_modifier_form_FRAIS' />
                                        <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
          </td>
        </tr>
      </table>
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_modifier_form_FRAIS($dat_ope,$tim_ope,$ido,$type,$debit,$com,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope     = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope     = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $ido         = mysqli_real_escape_string(self::$connexion,$ido);
      $type        = mysqli_real_escape_string(self::$connexion,$type);
      $debit       = mysqli_real_escape_string(self::$connexion,$debit);
      $com         = mysqli_real_escape_string(self::$connexion,$com);
      $http_link   = mysqli_real_escape_string(self::$connexion,$http_link);
    }

    if ( $dat_ope != '' && $ido != '' && $debit != 0 ) {

      $idu=$_SESSION['idu'];

      $datime_ope=$dat_ope.' '.$tim_ope;
      $query = "UPDATE V6_historique_operations SET datime_ope='$datime_ope',
                                             debit='$debit',
                                             com='$com',
                                             http_link='$http_link', 
                                             need_update='1' 
                                             WHERE idu='$idu' AND ido='$ido' LIMIT 1";    
      dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);

    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_modifier_form_ATTRIBUTION_GRATUITE($ido) {

    $query = "SELECT DATE(datime_ope),TIME(datime_ope),idp,type,credit,idv,nb,cours,com,http_link FROM V6_historique_operations WHERE ido='$ido'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list($dat_ope,$tim_ope,$idp,$type,$credit,$idv,$nb,$cours,$com,$http_link) =  mysqli_fetch_row($result);
    }

    ?>
    <form id='form_ATTRIBUTION_GRATUITE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='post'> 
      <table class='saisie'>
        <tr><th id='header' colspan='2'>Attribution d'actions gratuites</th></tr>
        <tr>
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo $dat_ope ?>'/></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo $tim_ope ?>' /></td>
        </tr>      
        <tr>
          <td><label for='idv'>Valeur</label></td>
          <td><?PHP self::afficher_selecteur_titre($idv); ?></td>
        </tr>
        <tr>
          <td><label for='nb'>Nombre</label></td>
          <td><input id='nb' name='nb' type='text' size='25' maxlength='25' value='<?PHP echo $nb ?>' /></td>
        </tr>
        <tr>
          <td><label for='cours'>Cours</label></td>
          <td><input id='cours' name='cours' type='text' size='25' maxlength='25' value='<?PHP echo $cours ?>' /></td>
        </tr>
        <tr>
          <td><label for='com'>Commentaire</label></td>
          <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' value='<?PHP echo $com ?>' /></td>
        </tr>
        <tr>
          <td><label for='http_link'>Lien</label></td>
          <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' value='<?PHP echo $http_link ?>'/></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
                                        <input type='hidden' name='ido' value='<?PHP echo $ido ?>' />
                                        <input type='hidden' name='action' value='traiter_modifier_form_ATTRIBUTION_GRATUITE' />
                                        <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
          </td>
        </tr>
      </table>  
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_modifier_form_ATTRIBUTION_GRATUITE($dat_ope,$tim_ope,$ido,$type,$com,$idv,$nb,$cours,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope     = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope     = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $ido         = mysqli_real_escape_string(self::$connexion,$ido);
      $type        = mysqli_real_escape_string(self::$connexion,$type);
      $idv         = mysqli_real_escape_string(self::$connexion,$idv);
      $nb          = mysqli_real_escape_string(self::$connexion,$nb);
      $cours       = mysqli_real_escape_string(self::$connexion,$cours);
      $com         = mysqli_real_escape_string(self::$connexion,$com);
      $http_link   = mysqli_real_escape_string(self::$connexion,$http_link);
    }

    if ( $dat_ope != '' && $ido != '' && $idv != 0 && $nb != 0 ) {

      $idu=$_SESSION['idu'];

      $datime_ope=$dat_ope.' '.$tim_ope;
      $query = "UPDATE V6_historique_operations SET datime_ope='$datime_ope',
                                                    idv='$idv',
                                                    nb='$nb',
                                                    cours='$cours',
                                                    com='$com',
                                                    http_link='$http_link', 
                                                    need_update='1' 
                                                    WHERE idu='$idu' AND ido='$ido' LIMIT 1";    
      dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);

    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }  
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_modifier_form_ORDRE_ACHAT_VENTE($ido) {

    $query = "SELECT DATE(datime_ope),TIME(datime_ope),idp,type,pcredit,pdebit,com,idv,nb,cours,courtage,tva,tb,http_link,id_strategie,stop,objectif,uts_ut,uts_tendance,uts_signal FROM V6_historique_operations WHERE ido='$ido'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list($dat_ope,$tim_ope,$idp,$type,$pcredit,$pdebit,$com,$idv,$nb,$cours,$courtage,$tva,$tb,$http_link,$id_strategie,$stop,$objectif,$uts_ut,$uts_tendance,$uts_signal) =  mysqli_fetch_row($result);
    }
    ?>
    <form id='form_ACHAT_VENTE' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='post'> 
      <table class='saisie'>
        <tr><th id='header' colspan='2'><?PHP echo $type ?> de Titres</th></tr>
        <tr>
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo $dat_ope ?>'/></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo $tim_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='id_strategie'>Stratégie</label></td>
          <td>
             <input type='text' id='id_strategie' size='30' value='<?PHP echo gerer_strategie::get_nom_strategie($id_strategie); ?>' readonly />
          </td>
        </tr>
        <tr>
          <td><label for='idv'>Valeur</label></td>
          <td>
             <input type='text' id='tags' size='30' value='<?PHP echo Cotations::get_val_name($idv); ?>' readonly />
             <input type='hidden' id='idv' name='idv' value='<?PHP echo $idv ?>' />
          </td>
        </tr>
        <tr>
          <td><label for='nb'>Nombre</label></td>
          <td><input id='nb' name='nb' type='text' size='<?PHP echo NBZ ?>' maxlength='<?PHP echo NBM ?>' value='<?PHP echo $nb ?>'/></td>
        </tr>
        <tr>
          <td><label for='cours'>Cours</label></td>
          <td><input id='cours' name='cours' type='text' size='<?PHP echo COZ ?>' maxlength='<?PHP echo COM ?>' value='<?PHP echo $cours ?>'/></td>
        </tr>
        <tr>
          <td><label for='courtage'>Courtage</label></td>
          <td><input id='courtage' name='courtage' type='text' size='<?PHP echo CTAZ ?>' maxlength='<?PHP echo CTAM ?>' value='<?PHP echo $courtage ?>'/></td>
        </tr>
        <tr>
          <td><label for='tva'>Tva</label></td>
          <td><input id='tva' name='tva' type='text' size='<?PHP echo TVAZ ?>' maxlength='<?PHP echo TVAZ ?>' value='<?PHP echo $tva ?>'/>
                <input id='check_courtage' name='check_courtage' type='checkbox' title='Mettre à zéro' checked='checked' />
          </td>
        </tr>
        <tr>
          <td><label for='tb'>Taxe&nbsp;Bancaire</label></td>
          <td><input id='tb' name='tb' type='text' size='<?PHP echo TBZ ?>' maxlength='<?PHP echo TBM ?>' value='<?PHP echo $tb ?>'/>
              <input id='check_tb' name='check_tb' type='checkbox' title='Mettre à zéro' checked='checked' />
          </td>
        </tr>
        <tr>
          <?PHP 
          if ( $type == 'ORDRE_ACHAT' ) { 
            echo "<td><label for='pdebit'>Investissement</label></td>\n";
            printf("<td><input id='pdebit' name='pdebit' type='text' size='%s' maxlength='%s' value='%s' /></td>\n",ZDZ,ZDM,$pdebit);
          }elseif ( $type == 'ORDRE_VENTE') {
            echo "<td><label for='pcredit'>Crédit dégagé</label></td>\n";
            printf("<td><input id='pcredit' name='pcredit' type='text' size='%s' maxlength='%s' value='%s' /></td>\n",ZCZ,ZCM,$pcredit);
          }
          ?>
        </tr>
        <tr>
          <td><label for='stop'>Stop</label></td>
          <td><input id='stop' name='stop' type='text' size='<?PHP echo TBZ ?>' maxlength='<?PHP echo TBM ?>' value='<?PHP echo $stop ?>'/></td>
        </tr>
        <tr>
          <td><label for='objectif'>Objectif</label></td>
          <td><input id='objectif' name='objectif' type='text' size='<?PHP echo TBZ ?>' maxlength='<?PHP echo TBM ?>' value='<?PHP echo $objectif ?>'/></td>
        </tr>
        <tr>
          <td><label for='uts_ut'>UT</label></td>
          <td><?PHP Edition_Trading::afficher_select_uts_ut($uts_ut); ?></td>
        </tr>
        <tr>
          <td><label for='uts_tendance'>Tendance sur UT</label></td>
          <td><?PHP Edition_Trading::afficher_select_uts_tendance($uts_tendance); ?></td>
        </tr>
        <tr>
          <td><label for='uts_signal'>Signal sur UT</label></td>
          <td><?PHP Edition_Trading::afficher_select_uts_signal($uts_signal); ?></td>
        </tr>
        <tr>
          <td><label for='com'>Commentaire</label></td>
          <td><input id='com' name='com' type='text' size='<?PHP echo COMZ ?>' maxlength='<?PHP echo COMM ?>' value='<?PHP echo $com ?>' /></td>
        </tr>
        <tr>
          <td><label for='http_link'>Lien</label></td>
          <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' value='<?PHP echo $http_link ?>'/></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
                                        <input type='hidden' name='ido' value='<?PHP echo $ido ?>' />
                                        <input type='hidden' id='type' name='type' value='<?PHP echo $type ?>' />
                                        <input type='hidden' name='action' value='traiter_modifier_form_ORDRE_ACHAT_VENTE' />
                                        <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
          </td>
        </tr>
      </table>
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_modifier_form_ORDRE_ACHAT_VENTE($dat_ope,$tim_ope,$ido,$type,$credit,$debit,$com,$idv,$nb,$cours,$courtage,$tva,$tb,$http_link,$stop,$objectif,$uts_ut,$uts_tendance,$uts_signal) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope      = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope      = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $ido          = mysqli_real_escape_string(self::$connexion,$ido);
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
      $stop         = mysqli_real_escape_string(self::$connexion,$stop);
      $objectif     = mysqli_real_escape_string(self::$connexion,$objectif);
      $uts_ut       = mysqli_real_escape_string(self::$connexion,$uts_ut);
      $uts_tendance = mysqli_real_escape_string(self::$connexion,$uts_tendance);
      $uts_signal   = mysqli_real_escape_string(self::$connexion,$uts_signal);
    }

    if ( $dat_ope != '' && $ido != '' && $idv!=0 && $nb!=0 && $cours != 0 ) {

      $idu=$_SESSION['idu'];

      $datime_ope=$dat_ope.' '.$tim_ope;
      $query = "UPDATE V6_historique_operations SET datime_ope='$datime_ope',
                                                    credit='$credit',
                                                    debit='$debit',
                                                    com='$com',
                                                    idv='$idv',
                                                    nb='$nb',
                                                    cours='$cours',
                                                    courtage='$courtage',
                                                    tva='$tva',
                                                    tb='$tb',
                                                    stop='$stop',
                                                    objectif='$objectif',
                                                    uts_ut='$uts_ut',
                                                    uts_tendance='$uts_tendance',
                                                    uts_signal='$uts_signal',
                                                    http_link='$http_link',
                                                    need_update='1' 
                                                    WHERE idu='$idu' AND ido='$ido' LIMIT 1";    
      dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);

    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_modifier_form_CRD($ido) {

    $query = "SELECT DATE(datime_ope),TIME(datime_ope),idp,type,debit,com,http_link FROM V6_historique_operations WHERE ido='$ido'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list($dat_ope,$tim_ope,$idp,$type,$debit,$com,$http_link) =  mysqli_fetch_row($result);
    }

    ?>
    <form id='form_CRD' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='post'> 
      <table class='saisie'>
        <tr><th id='header' colspan='2'>Commission de Règlement Différé</th></tr>
        <tr>
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo $dat_ope ?>'/></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo $tim_ope ?>' /></td>
        </tr>      
        <tr>
          <td><label for='debit'>Commission de règlement différé</label></td>
          <td><input id='debit' name='debit' type='text' size='<?PHP echo ZDZ ?>' maxlength='<?PHP echo ZDM ?>' value='<?PHP echo $debit ?>'/></td>
        </tr>
        <tr>

          <td><label for='http_link'>Lien</label></td>
          <td><input id='http_link' name='http_link' type='text' size='<?PHP echo LIKZ ?>' maxlength='<?PHP echo LIKM ?>' value='<?PHP echo $http_link ?>'/></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'><input type='hidden' name='idp' value='<?PHP echo $idp ?>' />
                                        <input type='hidden' name='ido' value='<?PHP echo $ido ?>' />
                                        <input type='hidden' name='action' value='traiter_modifier_form_CRD' />
                                        <button class='bt-label-disk' type='submit' value="Enregistrer"/>Enregistrer</button>                        
          </td>
        </tr>
      </table>  
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_modifier_form_CRD($dat_ope,$tim_ope,$ido,$debit,$com,$http_link) {

    if(!get_magic_quotes_gpc()) {
      $dat_ope     = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope     = mysqli_real_escape_string(self::$connexion,$tim_ope);
      $ido         = mysqli_real_escape_string(self::$connexion,$ido);
      $debit       = mysqli_real_escape_string(self::$connexion,$debit);
      $com         = mysqli_real_escape_string(self::$connexion,$com);
      $http_link   = mysqli_real_escape_string(self::$connexion,$http_link);
    }

    if ( $dat_ope != '' && $ido != '' && $debit != '' ) {

      $idu=$_SESSION['idu'];

      $datime_ope=$dat_ope.' '.$tim_ope;
      $query = "UPDATE V6_historique_operations SET datime_ope='$datime_ope',
                                                    debit='$debit',
                                                    com='$com',
                                                    http_link='$http_link', 
                                                    need_update='1' 
                                                    WHERE idu='$idu' AND ido='$ido' LIMIT 1";    
      dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);

    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";   

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function afficher_selecteur_titre($idv_selected='') {

    $query = "SELECT idv,val_name FROM V4_cotations_titres ORDER BY val_name ASC";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    echo"<select id='idv' name='idv'>\n";
    if ($idv_selected == '0' ) echo"<option value='0' selected='selected'>Toutes</option>\n";  
        else echo"<option value='0'>Toutes</option>\n";   
    if ( mysqli_num_rows($result) ) {
      while ( list($idv,$val_name) =  mysqli_fetch_row($result) ) {
        if ($idv_selected == $idv )echo"<option value='$idv' selected='selected'>$val_name</option>\n";
        else echo"<option value='$idv' >$val_name</option>\n";
      }
    }
    echo"</select>\n";
  }    
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  function __construct($connexion) {
    self::$connexion=$connexion;
  }
  
  
}
?>