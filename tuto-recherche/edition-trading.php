<?PHP
class Edition_Trading {

  protected static $connexion;

  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_modifier_trading($ido,$id_strategie_selected) {

    $idu=$_SESSION['idu'];

    $query = "SELECT DATE(datime_ope),TIME(datime_ope),type,idv,cours,stop,objectif,uts_ut,uts_tendance,uts_signal,id_strategie FROM V6_historique_operations WHERE idu='$idu' AND ido='$ido'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    

    if ( mysqli_num_rows($result) ) {
      list($dat_ope,$tim_ope,$type,$idv,$cours,$stop,$objectif,$uts_ut,$uts_tendance,$uts_signal,$id_strategie) =  mysqli_fetch_row($result);
    }
    ?>
    <form id='form_modifier_trading' action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method='get'> 
      <table class='saisie'>
        <tr><th id='header' colspan='2'>Modifier Données de trading</th></th></tr>
        <tr>
          <td><label for='dat_ope'>Date&nbsp;AAAA&#8209;MM&#8209;JJ</label></td>
          <td><input id='dat_ope' name='dat_ope' type='text' size='10' maxlength='10' value='<?PHP echo $dat_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='tim_ope'>Heure&nbsp;HH:MM:SS</label></td>
          <td><input id='tim_ope' name='tim_ope' type='text' size='8' maxlength='8' value='<?PHP echo $tim_ope ?>' /></td>
        </tr>
        <tr>
          <td><label for='id_strategie'>Stratégie</label></td>
          <td>
             <?PHP Gerer_Strategie::afficher_selecteur_strategie($id_strategie); ?>
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
          <td><label for='objectif'>Objectif</label></td>
          <td><input id='objectif' name='objectif' type='text' size='<?PHP echo COZ ?>' maxlength='<?PHP echo COM ?>' value='<?PHP echo $objectif ?>'/></td>
        </tr>
        <tr>
          <td><label for='stop'>Stop</label></td>
          <td><input id='stop' name='stop' type='text' size='<?PHP echo COZ ?>' maxlength='<?PHP echo COM ?>' value='<?PHP echo $stop ?>'/></td>
        </tr>
        <tr>
          <td><label for='uts_ut'>UT</label></td>
          <td><?PHP self::afficher_select_uts_ut($uts_ut); ?></td>
        </tr>
        <tr>
          <td><label for='uts_tendance'>Tendance</label></td>
          <td><?PHP self::afficher_select_uts_tendance($uts_tendance); ?></td>
        </tr>
        <tr>
          <td><label for='uts_signal'>Signal</label></td>
          <td><?PHP self::afficher_select_uts_signal($uts_signal); ?></td>
        </tr>
        <tr>
          <td class='td-bt-label-left'><a class='bt-label-circle-close' href='javascript: window.history.go(-1)'/>Annuler</a></td>
          <td class='td-bt-label-right'>
                                        <input type='hidden' name='ido' value='<?PHP echo $ido ?>' />
                                        <input type='hidden' name='id_strategie_selected' value='<?PHP echo $id_strategie_selected ?>' />
                                        <input type='hidden' name='action' value='traiter_modifier_trading' />
                                        <button class='bt-label-disk' type='submit' >Enregistrer</button>                        
          </td>
        </tr>
      </table>
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_modifier_trading($ido,$dat_ope,$tim_ope,$stop,$objectif,$uts_ut,$uts_tendance,$uts_signal,$id_strategie) {

    if(!get_magic_quotes_gpc()) {

      $dat_ope      = mysqli_real_escape_string(self::$connexion,$dat_ope);
      $tim_ope      = mysqli_real_escape_string(self::$connexion,$tim_ope);

      $ido          = mysqli_real_escape_string(self::$connexion,$ido);
      $stop         = mysqli_real_escape_string(self::$connexion,$stop);
      $objectif     = mysqli_real_escape_string(self::$connexion,$objectif);
      $uts_ut       = mysqli_real_escape_string(self::$connexion,$uts_ut);
      $uts_tendance = mysqli_real_escape_string(self::$connexion,$uts_tendance);
      $uts_signal   = mysqli_real_escape_string(self::$connexion,$uts_signal);
      $id_strategie = mysqli_real_escape_string(self::$connexion,$id_strategie);

    }

    if ( $ido != '' ) {

      $idu=$_SESSION['idu'];

      $datime_ope=$dat_ope.' '.$tim_ope;
      $query = "UPDATE V6_historique_operations SET datime_ope='$datime_ope',
                                                    stop='$stop',
                                                    objectif='$objectif',
                                                    uts_ut='$uts_ut',
                                                    uts_tendance='$uts_tendance',
                                                    uts_signal='$uts_signal',
                                                    id_strategie='$id_strategie'
                                                    WHERE idu='$idu' AND ido='$ido' LIMIT 1";    
      dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);

    } else echo "<div class='info'>Echec :: Formulaire incomplet</div>";
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_modifier_note($ido,$id_strategie_selected,$type_data) {

    $query = "SELECT note FROM V6_historique_operations WHERE ido='$ido'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list($note) =  mysqli_fetch_row($result);
    }
    ?>
      <table class='saisie'>
        <tr><th id='header' colspan='2'>Editer Note de Trading</th></tr>

        <tr>
          <td colspan='2' style='text-align:center;'><div id="dropzone">Drop an image from your computer</div></td>
        </tr>

        <tr>
          <td colspan='2' style='text-align:center;'>
          <?PHP 
          echo "<div id='box_$ido' class='editors_box'>\n"; 
          echo "<div id='pannel_$ido' class='editors_pannel'></div>\n"; 
          echo "<div id='div_edit_$ido' class='editors_div_edit' contentEditable >$note</div>\n";
          echo "</div>\n"; 
          ?>
        </td>
          
        </tr>
        <tr>
          <td class='td-bt-label-left'>
          <?PHP
          echo "<a class='bt-label-circle-close' href=\"javascript:document.location.href='/trades-V6/index-edition-trading.php?id_strategie_selected=$id_strategie_selected';\">Annuler</a>";        
          ?>
          </td>
          <td class='td-bt-label-right'>
            <input type='hidden' id='id_strategie_selected' name='id_strategie_selected' value='<?PHP echo $id_strategie_selected ?>' />
            <input type='hidden' id='type_data' name='type_data' value='<?PHP echo $type_data ?>' />
            <button id='enregistrer_note_<?PHP echo $ido ?>' class='bt-label-disk enregistrer_note' >Enregistrer</button>
          </td>
        </tr>
      </table>
      
      
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function afficher_historique_trading_V2($id_strategie_selected,$ids,$slide_size) {

    $idu=$_SESSION['idu'];
    
    $deb = ($ids - 1) * $slide_size;
    $offset = $slide_size;

    if ( $id_strategie_selected == -1 ) 

      $query = "SELECT ido,idp,datime_ope,type_data,type,idv,cours,stop,objectif,uts_ut,uts_tendance,uts_signal,note,id_strategie,plan_etat FROM V6_historique_operations WHERE idu='$idu' ORDER BY datime_ope DESC LIMIT $deb,$offset";

    else 

      $query = "SELECT ido,idp,datime_ope,type_data,type,idv,cours,stop,objectif,uts_ut,uts_tendance,uts_signal,note,id_strategie,plan_etat FROM V6_historique_operations WHERE idu='$idu' AND id_strategie='$id_strategie_selected' ORDER BY datime_ope DESC LIMIT $deb,$offset";

    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {

      echo "<table id='historique_trading' class='data'>\n";
      while ( list($ido,$idp,$datime_ope,$type_data,$type,$idv,$cours,$stop,$objectif,$uts_ut,$uts_tendance,$uts_signal,$note,$id_strategie,$plan_etat) =  mysqli_fetch_row($result) ) {

        $nom_strategie = Gerer_Strategie::get_nom_strategie($id_strategie);
        $my_date=Util::format_date($datime_ope);
        $compte_nom=self::get_compte_nom($idp);

        if ( $type_data == 'ANALYSE' || $type_data == 'PLAN_TRADE'  ) { 

		  $resume = substr($note,0,strpos($note,"<"));
		  
          echo "<tr id='$ido'>\n";

          echo "<td style='text-align:center; width:60px;'><button id='detail_trade_$ido' class='detail_trade' title='Details Trading $ido'>DT</button></td>\n";        

          echo "<td style='text-align:center;'>$my_date<br/>$nom_strategie<br/>$compte_nom</td>\n";

          if ( $type_data == 'ANALYSE' ) {
            echo "<td style='text-align:center;'>$type_data</td>\n";
            echo "<td colspan='5' class='tal'>$resume</td>\n";
          }

          if ( $type_data == 'PLAN_TRADE' ) {
            echo "<td style='text-align:center;'>$type_data</td>";
            echo "<td style='text-align:center;'>";
            self::afficher_selecteur_plan_etat($ido,$plan_etat);
            echo "</td>\n";
            echo "<td colspan='4' class='tal'>$resume</td>\n";
          }


          /* ************************************************ */ 
          echo "<td style='text-align:center; width:60px;'><a class='bt-icon-trash' href=\"javascript:confirm_delete('index-edition-trading.php?action=supprimer_note&ido=$ido&id_strategie_selected=$id_strategie_selected')\" title='Supprimer'>&nbsp;</a></td>\n";
          echo "<td style='text-align:center; width:60px;'><a class='bt-icon-script' href='index-edition-trading.php?action=print_modifier_note&ido=$ido&id_strategie_selected=$id_strategie_selected&type_data=$type_data'  title='Editer'>&nbsp;</a></td>\n";        

          echo "</tr>\n";

        }

        if ( $type_data == 'NOTE_TRADE' && ($type == 'ACHAT' || $type == 'VENTE' || $type == 'ORDRE_ACHAT' || $type == 'ORDRE_VENTE') ) { 

          echo "<tr id='$ido'>\n";

          echo "<td style='text-align:center; width:60px;'><button id='detail_trade_$ido' class='detail_trade' title='Details Trading $ido'>DT</button></td>\n";        

          echo "<td style='text-align:center;'>$my_date<br/>$nom_strategie<br/>$compte_nom</td>\n";

          $val_name=Cotations::get_val_name($idv);
          echo "<td style='text-align:center;'>$type</td>\n";
          echo "<td style='text-align:center;'>$val_name</td>\n";

          //echo "<td>$objectif&nbsp;&euro;<br/>$cours&nbsp;&euro;<br/>$stop&nbsp;&euro;</td>\n";


          if ( $objectif != 0 ) echo "<td>$objectif&nbsp;&euro;<br/>";
          else echo "<td>&nbsp;<br/>";
          
          echo "$cours&nbsp;&euro;<br/>";
          
          if ( $stop != 0 ) echo "$stop&nbsp;&euro;</td>\n";
          else echo "&nbsp;</td>\n";


          echo "<td>$uts_ut</td>\n";
          echo "<td>$uts_tendance</td>\n";
          echo "<td>$uts_signal</td>\n";

          /* ************************************************ */ 
          echo "<td style='text-align:center; width:60px;'><a class='bt-icon-pencil' href='index-edition-trading.php?action=print_modifier_trading&ido=$ido&id_strategie_selected=$id_strategie_selected'  title='Modifier'>&nbsp;</a></td>\n";        
          echo "<td style='text-align:center; width:60px;'><a class='bt-icon-script' href='index-edition-trading.php?action=print_modifier_note&ido=$ido&id_strategie_selected=$id_strategie_selected&type_data=NOTE_TRADE'  title='Editer Note'>&nbsp;</a></td>\n";        

          echo "</tr>\n";

        }
        
        
      }
      echo "</table>\n";
    }
  }
  //-------------------------------------------------------------------------------------------------------------------
  public static function compute_slide($id_strategie_selected,$slide_size) {

    $idu=$_SESSION['idu'];

    if ( $id_strategie_selected == -1 )
      $query = "SELECT COUNT(ido) FROM V6_historique_operations WHERE idu='$idu' AND (type='ACHAT' OR type='VENTE' OR type='ORDRE_ACHAT' OR type='ORDRE_VENTE')";
    else
      $query = "SELECT COUNT(ido) FROM V6_historique_operations WHERE idu='$idu' AND id_strategie='$id_strategie_selected' AND (type='ACHAT' OR type='VENTE' OR type='ORDRE_ACHAT' OR type='ORDRE_VENTE')";
    
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    list($nb_ope) =  mysqli_fetch_row($result);


    $slide_nb = Ceil($nb_ope / $slide_size) ;

    $slide_max = 10;

    // Limiter le nombre de tranches
    if ( $slide_nb > $slide_max ) {

      $slide_nb   = $slide_max ;
      $max_res    = $slide_max * $slide_size;
    
      echo "<div class='info'>Vous pouvez voir seulement $max_res opérations</div>\n";
    }

    return $slide_nb;
  }
  //-------------------------------------------------------------------------------------------------------------------
  public static function make_link($ids,$slide_nb,$slide_size,$id_strategie_selected) {

    if ( $slide_nb > 1 ) {
      echo "<div id='linkbar'>\n";
      echo "<ul>\n";

      // Faire la liste des liens qui pointe sur les requêtes de tranches
      for ( $is = 1 ; $is <= $slide_nb ; $is++ ) {

        echo "<li>";

          if ( (int)$ids == $is ) $class = "class='active'";
          else                    $class = "";
        
          printf("<a $class href=\"/trades-V6/index-edition-trading.php?action=print_edition_trading&ids=%s&slide_size=%s&id_strategie_selected=%s\">%s</a>",$is,$slide_size,$id_strategie_selected,$is);

        echo "</li>\n";
      }
      echo "</ul>\n";
      echo "</div>\n";
    }
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_form_filtre_edition_trading($id_strategie_selected,$slide_size,$etat_strategie_radio) {
  ?>
    <form id='print_form_filtre_edition_trading' action='<?PHP echo $_SERVER['PHP_SELF'] ?>' method='get'>
    <input id='slide_size' type='text' size='3' name='slide_size' value='<?PHP echo $slide_size ?>'>
    <label for='radio-1'>Archiver</label>
    <input type='radio' id='radio-1' class='etat_strategie_radio' name='etat_strategie_radio' value='ARCHIVER' <?PHP if ( $etat_strategie_radio == 'ARCHIVER' ) echo "checked='checked'" ?> />
    <label for='radio-2'>Terminer</label>
    <input type='radio' id='radio-2' class='etat_strategie_radio' name='etat_strategie_radio' value='TERMINER' <?PHP if ( $etat_strategie_radio == 'TERMINER' ) echo "checked='checked'" ?> />
    <label for='radio-3'>En Cours</label>
    <input type='radio' id='radio-3' class='etat_strategie_radio' name='etat_strategie_radio' value='EN_COURS' <?PHP if ( $etat_strategie_radio == 'EN_COURS' ) echo "checked='checked'" ?>/>
    <?PHP
    $query = "SELECT id_strategie,nom FROM V6_liste_strategies WHERE etat_strategie='$etat_strategie_radio'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);
    if ( mysqli_num_rows($result) ) {

      echo "<select id='id_strategie_selected' name='id_strategie_selected'>\n";

      if ( $id_strategie_selected == -1 ) echo "<option value='-1' selected='selected'>Toutes</option>\n";
      else echo "<option value='-1'>Toutes</option>\n"; 

      if ( $id_strategie_selected == 0 ) echo "<option value='0' selected='selected'>Aucune</option>\n";
      else echo "<option value='0'>Aucune</option>\n"; 

      while (list($id_strategie,$nom) = mysqli_fetch_row($result)) {
        if ( $id_strategie_selected == $id_strategie ) echo "<option value='$id_strategie' selected='selected'>$nom</option>\n";
        else echo "<option value='$id_strategie'>$nom</option>\n"; 
      }
      echo "</select>\n";
    }    
    ?>
    <input type='hidden' name='action' value='print_edition_trading' />
    <input type='submit' id='filtrer_strategie' value='Filtrer' />
    </form>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function print_bouton_plan_analyse($id_strategie_selected) {
  ?>
    <table class='data'>
      <tr>
        <td>
          <form id='print_form_bouton_plan_analyse' action='<?PHP echo $_SERVER['PHP_SELF'] ?>' method='get'>
          <input type='hidden' name='action' value='traiter_creer_note' />
          <input type='hidden' name='id_strategie_selected' value='<?PHP echo $id_strategie_selected ?>' />
          <input type='hidden' name='type_data' value='PLAN_TRADE' />
          <input type='submit' class='plan_analyse' value='Creer plan de Trade' />
          </form>
        </td>
        
        <td><?PHP self::afficher_scenario_strategie($id_strategie_selected) ?></td>
        
        <td>
          <form id='print_form_bouton_plan_analyse' action='<?PHP echo $_SERVER['PHP_SELF'] ?>' method='get'>
          <input type='hidden' name='action' value='traiter_creer_note' />
          <input type='hidden' name='id_strategie_selected' value='<?PHP echo $id_strategie_selected ?>' />
          <input type='hidden' name='type_data' value='ANALYSE' />
          <input type='submit' class='plan_analyse' value='Creer Analyse' />
          </form>
        </td>
      </tr>
    </table>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function traiter_creer_note($id_strategie_selected,$type_data) {

    if ( $id_strategie_selected != 0 && $type_data != '' ) {

      $idu=$_SESSION['idu'];

      $query = "INSERT INTO V6_historique_operations(datime_ope,type_data,id_strategie,idu) 
                VALUES (now(),'$type_data','$id_strategie_selected','$idu')";
    
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
  public static function afficher_scenario_strategie($id_strategie_selected) {    

    $query = "SELECT id_strategie,nom,scenario FROM V6_liste_strategies WHERE id_strategie='$id_strategie_selected'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);

    if ( mysqli_num_rows($result) ) {

      list($id_strategie,$nom,$scenario) = mysqli_fetch_row($result);
      $scenario=nl2br($scenario);
      echo "<div class='strategie_box'>\n";
      echo "<button id='strategie_nom' class='tac'>Stratégie :: $id_strategie :: $nom</button>\n";
      echo "<div id='strategie_scenario'>$scenario</div>\n";
      echo "</div>\n";
    }
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function supprimer_note($ido) {    

    $idu=$_SESSION['idu'];
  
    $query = "DELETE FROM V6_historique_operations WHERE ido='$ido' AND idu='$idu' LIMIT 1";
    dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);

  }

  /*--------------------------------------------------------------------------------------------------*/
  public static function print_accueil_edition_trading($id_strategie_selected,$etat_strategie_radio,$ids,$slide_size) {

    self::print_form_filtre_edition_trading($id_strategie_selected,$slide_size,$etat_strategie_radio);

    $slide_nb = self::compute_slide($id_strategie_selected,$slide_size);

    self::make_link($ids,$slide_nb,$slide_size,$id_strategie_selected);
  
    if ( $id_strategie_selected != 0 && $id_strategie_selected != -1  ) self::print_bouton_plan_analyse($id_strategie_selected);

    self::afficher_historique_trading_V2($id_strategie_selected,$ids,$slide_size);

  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function afficher_selecteur_plan_etat($ido,$plan_etat) {

    echo"<select id='plan_etat_$ido' class='plan_etat' name='plan_etat'>\n";
    if ($plan_etat == 'EN_COURS' ) echo"<option value='EN_COURS' selected='selected'>EN_COURS</option>\n";
    else echo"<option value='EN_COURS'>EN_COURS</option>\n";
    if ($plan_etat == 'EXECUTER' ) echo"<option value='EXECUTER' selected='selected'>EXECUTER</option>\n";
    else echo"<option value='EXECUTER'>EXECUTER</option>\n";
    if ($plan_etat == 'AVORTER' ) echo"<option value='AVORTER' selected='selected'>AVORTER</option>\n";
    else echo"<option value='AVORTER'>AVORTER</option>\n";
    echo"</select>\n";


  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function afficher_select_uts_ut($uts_ut = 'no_value') {
    $uts_ut = strtolower($uts_ut);
  ?>
    <select name='uts_ut'>
    <option value='no_value'  <?PHP if ($uts_ut == 'no_value' ) echo "selected='selected'" ?> >No Value</option>
    <option value='weekly'    <?PHP if ($uts_ut == 'weekly')    echo "selected='selected'" ?> >WEEKLY</option>
    <option value='daily'     <?PHP if ($uts_ut == 'daily')     echo "selected='selected'" ?> >DAILY</option>
    <option value='4heures'   <?PHP if ($uts_ut == '4heures')   echo "selected='selected'" ?> >4 HEURES</option>
    <option value='horaire'   <?PHP if ($uts_ut == 'horaire')   echo "selected='selected'" ?> >HORAIRE</option>
    <option value='30Minutes' <?PHP if ($uts_ut == '30minutes') echo "selected='selected'" ?> >30 Minutes</option>
    </select>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function afficher_select_uts_tendance($uts_tendance = 'no_value') {
    $uts_tendance = strtolower($uts_tendance);
  ?>
    <select name='uts_tendance'>
    <option value='no_value'  <?PHP if ($uts_tendance == 'no_value')  echo "selected='selected'" ?> >No Value</option>
    <option value='haussiere' <?PHP if ($uts_tendance == 'haussiere') echo "selected='selected'" ?> >HAUSSIERE</option>
    <option value='neutre'    <?PHP if ($uts_tendance == 'neutre')    echo "selected='selected'" ?> >NEUTRE</option>
    <option value='baissiere' <?PHP if ($uts_tendance == 'baissiere') echo "selected='selected'" ?> >BAISSIERE</option>
    <option value='range'     <?PHP if ($uts_tendance == 'range')     echo "selected='selected'" ?> >RANGE</option>
    </select>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function afficher_select_uts_signal($signal = 'no_value') {
  ?>
    <select name='uts_signal'>

    <option value='no_value'              <?PHP if ($signal == 'no_value')            echo "selected='selected'" ?> >No Value</option>

    <option value='aucune'                <?PHP if ($signal == 'aucun')               echo "selected='selected'" ?> >Aucun</option>

    <option value='div_macd_baissiere'    <?PHP if ($signal == 'div_macd_baissiere')  echo "selected='selected'" ?> >Divergence MACD baissière</option>
    <option value='div_macd_haussiere'    <?PHP if ($signal == 'div_macd_haussiere')  echo "selected='selected'" ?> >Divergence MACD haussière</option>

    <option value='3_rsi_surachat'        <?PHP if ($signal == '3_rsi_surachat')      echo "selected='selected'" ?> >Les 3 RSI en surachat</option>
    <option value='3_rsi_survente'        <?PHP if ($signal == '3_rsi_survente')      echo "selected='selected'" ?> >Les 3 RSI en survente</option>

    <option value='2_rsi_surachat'        <?PHP if ($signal == '2_rsi_surachat')      echo "selected='selected'" ?> >Les 2 RSI en surachat</option>
    <option value='2_rsi_survente'        <?PHP if ($signal == '2_rsi_survente')      echo "selected='selected'" ?> >Les 2 RSI en survente</option>

    <option value='Williams_surachat'   <?PHP if ($signal == 'Williams_surachat') echo "selected='selected'" ?> >Williams SurAchat</option>    
    <option value='Williams_survente'   <?PHP if ($signal == 'Williams_survente') echo "selected='selected'" ?> >Williams SurVente</option>    

    <option value='achat-resistance'      <?PHP if ($signal == 'achat-resistance')    echo "selected='selected'" ?> >Achat d'une résistance</option>
    <option value='vente-resistance'      <?PHP if ($signal == 'vente-resistance')    echo "selected='selected'" ?> >Vente d'une résistance</option>

    <option value='achat-support'         <?PHP if ($signal == 'achat-support')       echo "selected='selected'" ?> >Achat d'un support</option>
    <option value='vente-support'         <?PHP if ($signal == 'vente-support')       echo "selected='selected'" ?> >Vente d'un support</option>

    <option value='achat-sur-repli'       <?PHP if ($signal == 'achat-sur-repli')     echo "selected='selected'" ?> >Achat sur repli</option>
    <option value='vente-sur-rebond'      <?PHP if ($signal == 'vente-sur-rebond')    echo "selected='selected'" ?> >Vente sur rebond</option>

    <option value='Squeez_10MN'           <?PHP if ($signal == 'Squeez_10MN')         echo "selected='selected'" ?> >Squeez sur le 10MN</option>
    <option value='Squeez_1H'             <?PHP if ($signal == 'Squeez_1H')           echo "selected='selected'" ?> >Squeez en horaire</option>
    <option value='Squeez_Daily'          <?PHP if ($signal == 'Squeez_Daily')        echo "selected='selected'" ?> >Squeez sur le daily</option>

    <option value='chiffre_eco'           <?PHP if ($signal == 'chiffre_eco')         echo "selected='selected'" ?> >Trade sur chiffres éco</option>
    <option value='futures_us'            <?PHP if ($signal == 'futures_us')          echo "selected='selected'" ?> >Trade sur écart avec futures US</option>
    <option value='combler_gap'           <?PHP if ($signal == 'combler_gap')         echo "selected='selected'" ?> >Comblement de Gap Commun</option>
    <option value='cassure-resistance'    <?PHP if ($signal == 'cassure-resistance')  echo "selected='selected'" ?> >Cassure de résistance</option>
    <option value='suivre-tendance'       <?PHP if ($signal == 'suivre-tendance')     echo "selected='selected'" ?> >Suivre la tendance</option>
    <option value='renforcer-tendance'    <?PHP if ($signal == 'renforcer-tendance')  echo "selected='selected'" ?> >Renforcer dans la tendance</option>
    <option value='canal-ascendant'       <?PHP if ($signal == 'canal-ascendant')     echo "selected='selected'" ?> >Support de canal ascendant</option>
    <option value='evenement_politique'   <?PHP if ($signal == 'evenement_politique') echo "selected='selected'" ?> >Evènement politique</option>    
    </select>
  <?PHP
  }
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  public static function get_compte_nom($idp) {

    $query = "SELECT nom FROM V6_liste_comptes WHERE idp='$idp'";
    $result = dtbi_query(self::$connexion,$query,__FILE__,__LINE__,0);    
    if ( mysqli_num_rows($result) ) {
      list($nom) =  mysqli_fetch_row($result);
      return($nom);     
    }
  }
  
  /*----------------------------------------------------------------------------------------------------------------------------------*/
  function __construct($connexion) {
	self::$connexion=$connexion;
  }
    
}
?>