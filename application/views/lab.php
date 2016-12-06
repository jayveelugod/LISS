<?php $this->load->view('js'); ?>

<!DOCTYPE html>
<html>
  <head>
  
    <title>Laboratory Equipment Inventory Software System</title>

    <!-- Bootstrap CSS -->    
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="<?php echo base_url(); ?>css/bootstrap-theme.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="<?php echo base_url(); ?>css/elegant-icons-style.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>css/font-awesome.min.css" rel="stylesheet" />    

    <!-- Custom styles -->
	<link href="<?php echo base_url(); ?>css/widgets.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/style-responsive.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>css/jquery-ui-1.10.4.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/custom.css" rel="stylesheet">
    
  </head>

  <body>
  <!-- container section start --> 
      <section id="container" class="">

      <div id='labContent'>
        <!--main content start-->
        <section class="wrapper"> 
           <!--overview start-->  
        <div class="row">
        <div class="col-lg-12">
           <h3 class="page-header"><i class="icon_menu-square_alt2"></i><?php echo $equipList[0][0]['labName']; ?>
          <a class="btn btn-danger btn-lg pull-right" data-toggle="modal" data-target="#deleteModal" style="margin-top: -1%">Delete Laboratory</a></h3>
        </div>
        </div>

        <div class="row">
          <div class="col-lg-12">          
            <ol class="breadcrumb">
               <input type="hidden" value='<?php echo $equipList[0][0]['labID']; ?>' id='labID'>
               <li><i class=" icon_menu-square_alt2"></i>All</li>
               <li class="pointer"><i class="arrow_carrot-2up_alt2"></i><a data-toggle="modal" data-target="#borrowModal">Borrow</a></li>
               <li class="pointer"><i class="arrow_triangle-down_alt2"></i><a data-toggle="modal" data-target="#returnModal">Return</a></li>
               <li class="pointer"><i class=" icon_error-circle_alt"></i><a data-toggle="modal" data-target="#damageModal">File Damaged Equipment</a></li>         
            </ol>

          <div class="input-group">
               <input type="text" class="form-control" placeholder="Search for..." id="searchEquipment">
                  <span class="input-group-btn">
                    <button class="btn btn-default form-control" type="button"><i class=" icon_search"></i></button>
                  </span>       
          </div>             
            
          </br>              
           
          <section class="panel panel-primary ">
            <table class="table table-striped table-advance table-hover" id="labEquipmentsTable">
              <thead><tr>
                   <th class="th"><i class="icon_tag"></i> Serial No.</th>
                   <th class="th"><i class="icon_clipboard"></i> Name</th>
                   <th class="th"><i class="icon_cogs"></i> Actions
                      <img src="<?php echo base_url();?>img/icons/move-icon.png" class="move-icon" data-target="#moveModal" data-toggle="modal" rel="tooltip" title="Move">               
                   </th> 
              </tr></thead>
                <tbody>
                <?php if(null != $equipList[1]){
                        for($i = 0; $i < count($equipList[1]); $i++){?>
                          <tr>
                            <td><?php echo $equipList[1][$i]['eqpSerialNum'];?></td>
                            <td><?php echo $equipList[1][$i]['eqpName'];?></td>
                            <td>
                              <div class="btn-group">
                                <a class="btn btn-primary" onclick = "editEquipment('<?php echo $equipList[1][$i]['eqpSerialNum']; ?>')" id="<?php echo $equipList[1][$i]['eqpSerialNum']; ?>"  value="<?php echo $equipList[1][$i]['eqpSerialNum']; ?>" rel="tooltip" title="Edit"><i class="icon_pencil"></i></a>
                                <a class="btn btn-success" onclick = "viewEquipmentHistory('<?php echo $equipList[1][$i]['eqpSerialNum']; ?>', '<?php echo $equipList[1][$i]['eqpName']; ?>')" id="<?php echo $equipList[1][$i]['eqpSerialNum']; ?>"  value="<?php echo $equipList[1][$i]['eqpSerialNum']; ?>" rel="tooltip" title="View Equipment History"><i class=" icon_search-2" ></i></a>
                              </div>
                              <input type="checkbox" class="check" name="checkItem">
                            </td>
                          </tr>
                  <?php } 
                      }else{
                  ?>
                   <tr>
                      <td>No records to display..</td>
                      <td></td>
                      <td></td>
                   </tr>
                  <?php } ?>
                </tbody>
             </table>
          </section>
       </section>

      </div>
      
       <!-- modals -->
       <div id="deleteModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Delete Laboratory</h2>
              </div>
              <div class="modal-body" align="center">
               Are you sure you want to delete <?php echo $equipList[0][0]['labName']; ?>?
              </div>
              <div class="modal-footer">
                <button type="button"  class="btn btn-success btn-lg modalBtn" id='deleteLabBtn'>Delete Laboratory</button>
                 <button type="button"  class="btn btn-danger btn-lg modalBtn" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
       </div>

       <div id="moveModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Move Equipment</h2>
              </div>
              <div class="modal-body" >
               <table align="center" width="60%">
                <tr>
                   <td align="center">Serial No</td><td>this serial</td>
                 </tr><tr>
                   <td align="center">Name </td><td>this name</td>
                 </tr><tr>
                   <td align="center">From </td><td> this laboratory </td>
                 </tr><tr>
                   <td align="center">To </td>
                   <td><select class="input">
                      <option selected="true">Laboratory 2</option>
                   </select></td>
                 </tr>
              </table>
              </div>
              <div class="modal-footer">
                <button type="button"  class="btn btn-success btn-lg modalBtn" >Save Changes</button>
                 <button type="button" class="btn btn-danger btn-lg modalBtn" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

       <div id="editModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Edit Equipment</h2>
              </div>
              <div class="modal-body" >
               <table align="center" width="60%">
                <tr>
                   <td align="center">Serial No</td><td><input type="text" class="input" id="editSerialNum" disabled></td>
                 </tr><tr>
                   <td align="center">Name </td><td><input type="text" class="input" id="editName"></td>
                 </tr><tr>
                   <td align="center">Price </td><td><input type="text" class="input" id="editPrice"></td>
                 </tr>
              </table>
              </div>
              <div class="modal-footer">
                <button type="button" id="editSaveBtn" class="btn btn-success btn-lg modalBtn" >Save Changes</button>
                 <button type="button" class="btn btn-danger btn-lg modalBtn" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

       <div id="vehModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title"></h2>
              </div>
              <div class="modal-body" >
              <br>
               <table align="center" class="table" width="80%">
                  <th>Date</th>
                  <th>Detail</th>
                  <tbody id="equipmentHistory">
                    <tr><td><span id="loadSpinner"><i class="fa fa-spinner fa-spin fa-5x fa-fw"></i></span></td></tr>
                  </tbody>                            
              </table>
              </div>
              <div class="modal-footer">                
                 <button type="button" class="btn btn-danger btn-lg modalBtn" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div id="borrowModal" class="modal fade" role="dialog">
           <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Borrow Equipment</h2>
              </div>
              <div class="modal-body" >
              <form>
               <table align="center" width="60%">
                <tr>
                   <td align="center">ID number </td><td><input type="number" onmouseup = "checkIDnumber(this.value)" onkeyup = "checkIDnumber(this.value)" class="input" id='borrowerID' required autofocus="true"></td> <td><i class="idNumCheck" aria-hidden="true"></i></td>
                 </tr>
                  <tr><td></td><td><span class="idNumValidate"></span></td></tr>
                 <tr>
                   <td align="center">Name </td><td><input type="text" onkeyup = "validate(this, event)" class="input" id='borrowerName' required autofocus="true"></td> <td><i class="nameCheck" aria-hidden="true"></i></td>
                 </tr>
                 <tr><td></td><td><span class="nameValidate"></span></td></tr>
                 <tr>
                   <td align="center">Teacher </td><td><input type="text" onkeyup = "validate(this, event)" class="input" id='borrowerTeacher' required autofocus="true"></td>
                   <td><i class="teacherCheck" aria-hidden="true"></i></td>
                 </tr>
                 <tr><td></td><td><span class="teacherValidate"></span></td></tr>
                 <tr>
                   <td align="center">In-charge </td><td><input type="text" onkeyup = "validate(this, event)" class="input" id='incharge' required autofocus="true"></td>
                   <td><i class="inchargeCheck" aria-hidden="true"></i></td>
                 </tr>
                 <tr><td></td><td><span class="inchargeValidate"></span></td></tr>
              </table>
              </br>

            <div class="parentDiv">
                <div id="borrowModalInnerDiv">
                  <input type="text" class="input" id="searchBorrowed" placeholder="Search equipments">
                  <div id="borrowModalInnerDiv2">
                      <table class="table first "  id="borrowedEquipList">
                       <tr id='borrowmModalHeader'>
                          <th><u>All Equipments</u></th>
                          <th align="right"><input type="checkbox" onclick = "checkAllBorrow()" class="returnItem"></th> 
                        </tr>
                        <tbody id="borrowedList">
                          <td>No records to display...</td>
                          <td></td>
                        </tbody>
                    </table>
                  </div>
                </div>
        
                <div class="floatRightWidth49">
                 Borrowed Equipments:                 
                      <table class="table" >
                        <thead class="displayBlock">
                            <th class="width80">Equipment</th>
                        </thead>
                        <tbody id="borrowedEquipments" class="tbodyData"></tbody>
                       </table>
               </div>
            </div>
            
         </div>
              <div class="modal-footer">
                <button type="submit" id="borrowBtn" class="btn btn-success btn-lg modalBtn" >Borrow Equipments</button>
                 <button type="button" class="btn btn-danger btn-lg modalBtn" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
          </form>
        </div>

        <div id="returnModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Return Equipment</h2>
              </div>
              <div class="modal-body" >
               <table align="center" width="60%">
                <tr>
                   <td align="center">ID number</td><td><input type="number" class="input" id="returnerID"></td>
                   <td><i class="idNumCheck" aria-hidden="true"></i></td>
                   <tr><td></td><td><span class="idNumValidate"></span></td></tr>
                 </tr><tr>
                   <td align="center">Name </td><td><input type="text" disabled class="input" id="returnerName"></td>      
                   <td><i class="nameCheck" aria-hidden="true"></i></td>           
              </table>
              <br>

              Borrowed Equipments:
                <table class="table" id="returnModalTable">
                        <thead id='returnModalHeader' class="th displayBlock">
                        </thead>
                        <tbody id="returnedEquipments">
                           <tr>
                            <td>No Records to display...</td>
                            <td></td>
                           </tr>
                        </tbody>
                    </table>

              </div>
              <div class="modal-footer">
                <button type="button" id="returnBtn" class="btn btn-success btn-lg modalBtn" >Return Equipments</button>
                 <button type="button" id="modalBtn" class="btn btn-danger btn-lg modalBtn" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div id="damageModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">File Damaged Equipment</h2>
              </div>
              <div class="modal-body" >
              <form>
               <table align="center" width="60%">
                <tr>
                   <td align="center">ID number </td><td><input type="number" onmouseup = "checkIDnumber(this.value)" onkeyup = "checkIDnumber(this.value)" class="input" id='damagerID' required autofocus="true"></td> <td><i class="idNumCheck" aria-hidden="true"></i></td>
                 </tr>
                 <tr><td></td><td><span class="idNumValidate"></span></td></tr>
                 
                 <tr><br>
                   <td align="center">Name </td><td><input type="text" onkeyup = "validate(this, event)" class="input" id='damagerName' required autofocus="true"></td><td><i class="nameCheck" aria-hidden="true"></i></td>
                 </tr>
                 <tr><td></td><td><span class="nameValidate"></span></td></tr>
                 <tr>
                   <td align="center">Teacher </td><td><input type="text" onkeyup = "validate(this, event)" class="input" id='damagerTeacher' required autofocus="true"></td>
                   <td><i class="teacherCheck" aria-hidden="true"></i></td>
                 </tr>
                 <tr><td></td><td><span class="teacherValidate"></span></td></tr>
              </table>
              </br>

              <div class="parentDiv">
                <div id="damageModalInnerDiv">
                  <input type="text" class="input" id="searchDamaged" placeholder="Search equipments">
                  <div id="damageModalInnerDiv2">
                      <table class="table first "  id="damagedEquipList">
                       <tr id="damagemModalHeader">
                          <th><u>All Equipments</u></th>
                          <th align="right"><input type="checkbox" onclick = "checkAllDamage()" class="damageItem"></th> 
                        </tr>
                        <tbody id="damagedList">
                          <td>No records to display...</td>
                          <td></td>
                        </tbody>
                    </table>
                  </div>
                </div>
        
                <div class="floatRightWidth49">
                 Damaged Equipments:                 
                      <table class="table" class="height95Width100">
                        <thead class="displayBlock">
                            <th class="width80">Equipment</th>
                            <th>Price</th>
                        </thead>
                        <tbody id="damagedEquipments" class="tbodyData"></tbody>
                       </table>
                  <span>Total: </span><span id="price"></span>
                </div>
            </div>

              </div>
              <div class="modal-footer">
                <button type="submit" id="damageBtn" class="btn btn-success btn-lg modalBtn" >File Damaged Equipments</button>
                 <button type="button" class="btn btn-danger btn-lg modalBtn" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
          </form>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="notifyModal" tabindex="-1" role="dialog" aria-labelledby="notifyModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header notifyHeader">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="notifyModalLabel"></h4>
              </div>
              <div class="modal-body notifyBody">
                <div id = 'divContent'></div>
              </div>
            </div>
          </div>
        </div>


        <div class="modal fade" id="processingModal" tabindex="-1" role="dialog" aria-labelledby="processingModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header nprocessingModalyHeader">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="processingModalLabel"></h4>
              </div>
              <div class="modal-body processingModalBody"></div>
            </div>
          </div>
        </div>


       </section>


     <!-- javascripts -->
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-ui-1.10.4.min.js"></script>
   <script src="<?php  echo base_url(); ?>js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.9.2.custom.min.js"></script>
    <!-- bootstrap -->
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    <!-- nice scroll -->
    <script src="<?php echo base_url(); ?>js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.nicescroll.js" type="text/javascript"></script>

   
    <!--custome script for all page-->
    <script src="<?php echo base_url(); ?>js/scripts.js"></script>
    <!-- custom script for this page-->   
    <script src="<?php echo base_url(); ?>js/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-jvectormap-world-mill-en.js"></script> 
    <script src="<?php echo base_url(); ?>js/jquery.autosize.min.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.placeholder.min.js"></script> 

  </body>
</html>
