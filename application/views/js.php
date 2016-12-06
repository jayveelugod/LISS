<!DOCTYPE html>
<html>
<head>
	<title>Laboratory Equipment Inventory Software System</title>


	<script src="<?php echo base_url(); ?>js/jquery.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery-ui-1.10.4.min.js"></script>
	<script src="<?php  echo base_url(); ?>js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.9.2.custom.min.js"></script>
	<!-- bootstrap -->
	<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
	<!-- nice scroll -->
	<script src="<?php echo base_url(); ?>js/jquery.scrollTo.min.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery.nicescroll.js" type="text/javascript"></script>


	<!-- custom script for this page-->   
	<script src="<?php echo base_url(); ?>js/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery-jvectormap-world-mill-en.js"></script> 
	<script src="<?php echo base_url(); ?>js/jquery.autosize.min.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery.placeholder.min.js"></script> 

	<script type="text/javascript">

		var equipListLoad = "";
		var borrowedEquipListLoad = "";
		var borrowedEquipmentsArray = [];
		var damagedEquipmentsArray = [];

		$(document).ready(function(){
			$("#all").click(function(){
				$("#all").addClass("active");
				$(".lab").removeClass("active");
				$("#addBtn").text("Add Laboratory");
				$("#frame").attr('src', "<?php echo site_url('Index/loadIframe/all');?>");
			});

			$("#addBtn").click(function(){
				$("#addBtn").attr("data-toggle", "modal");
				var htmlString = $(this).html();
				if(htmlString=="Add Laboratory"){
					$("#addBtn").attr("data-target", "#addLab");
				}
				if(htmlString=="Add Equipment"){
					$("#addBtn").attr("data-target", "#addEqpmnt");
				}
			});

			// added by JV
			// Add Equipment Module
			$("#addEquipment").click(function(){
				$.ajax({
					url: "<?php echo site_url('Equipment/addEquipment');?>",
					type: 'POST',
					data: {'eqpSerialNum': $("#eqpSerialNum").val(),
						'eqpName': $("#eqpName").val(),
						'type': $("input[name=item]:checked").val(),
						'eqpPrice':  $("#eqpPrice").val()},
					success: function(data){
						console.log(data);
						$("#addEqpmnt").removeClass("in");
						$(".modal-backdrop").remove();
						$("#addEqpmnt").hide();
						alert("Equipment Successfully Added!");
						$("#frame").attr('src', "<?php echo site_url('Index/loadIframe/lab');?>");
					}
				});
			});
			//END add Equipment Module

			// Edit Equipment
			$("#editSaveBtn").click(function(){
				$.ajax({
	        		url: "<?php echo site_url('Equipment/updateEquipment');?>",
	        		type: 'POST',
	        		data: {'eqpSerialNum': $("#editSerialNum").val(),
		        		'eqpName': $("#editName").val(),
		        		'eqpPrice': $("#editPrice").val()
		        	},
		        	success: function(data){
		        		console.log(data);
		        		$("#editModal").modal('hide');
		        		alert('Successfully Updated');
		        		// $("#frame").attr('src', "<?php echo site_url('Index/loadIframe/lab');?>");
		        		location.reload();
		        	}
		        });	
			});

			$("#addLabBtn").click(function(){
				$.ajax({
					url: "<?php echo site_url('Index/addLaboratory');?>",
					type: 'POST',
					data: {'labName': $("#labName").val(),
						'labLocation': $("#labLocation").val()},
					success: function(data){
						$("#addLab").modal('hide');
						$("#notifyModal").modal('show');
						$("#notifyModal .notifyHeader").html('Successfully Added Laboratory');
						$("#notifyModal #divContent").html($("#labName").val());  
						$("#notifyModal").on('hidden.bs.modal', function (e) {
								location.reload();
						});
					}
				});
			});
	        // END Edit Equipment
	        $.ajax({
	      	url: "<?php echo site_url('Equipment/loadAllEquipments');?>",
	      	type: 'GET',
	      	dataType: 'json',
	      	success: function(data){
	      		console.log(data);
	      		$("#searchAll").autocomplete({
	      			source: data,
	                 //if empty results
	                  response: function(event, ui) {
	                      if (ui.content.length === 0) {
	                         var noResult = { value:"",label:"No results found" };
                			  ui.content.push(noResult);
	                      } else {
	                         // $("#empty-message").empty();
	                      }
	                  },
	                  select: function(event, ui) {
	                  	var thisEquipment = ui.item.value;
	                  	$.ajax({
	                  		url: "<?php echo site_url('Equipment/searchEquipment');?>",
	                  		type: 'POST',
	                  		data: {'equipmentSerialNum': 'NULL',
	                  		'equipmentName': thisEquipment
	                  	},
	                  	success: function(data){
	                  		console.log(data);
	                  		var searchResult = "<tr>";
		                  		searchResult += "<td>"+data[0].eqpName+"</td>";
		                  		searchResult += "<td>"+data[0].quantity+"</td>";
		                  		searchResult += "</tr>";
	                  		$("#listAllEquipments tbody").html(searchResult);
	                  	}
	                  });
	                  }
	              });
	      		}
	      });
	       $("#searchAll").keyup(function(){
	      	if('' == $("#searchAll").val()){
	      		$.ajax({
	      			url: "<?php echo site_url('Equipment/getAllEquipmentsList');?>",
	      			type: 'GET',
	      			dataType: 'json',
	      			success: function(data){
	      				console.log(data);
	      				if(data.length != 0){
	      					var searchResult = "";
		      				for(var i = 0; i < data.length; i++){
		      					searchResult += "<tr>";
		      					searchResult += "<td>"+data[i].eqpName+"</td>";
		      					searchResult += "<td>"+data[i].quantity+"</td>";
		      					searchResult += "</tr>";
		      				}
		      				$("#listAllEquipments tbody").html(searchResult);
	      				}
	      			}
	      		});  
	      	}else{}
	      });   

	      $("#deleteLabBtn").click(function(){
	      		$.ajax({
	      			url: "<?php echo site_url('Index/deleteLab');?>",
	      			data: {'labID': $("#labID").val()},
	      			type: 'POST',
	      			dataType: 'json',
	      			success: function(data){
	      				$("#deleteModal").modal('hide');
	      			}
	      		});  
	      }); 
			// Viewing Laboratory 
	      // Search Equipment module
	      $.ajax({
	      	url: "<?php echo site_url('Equipment/getEquipments');?>",
	      	type: 'POST',
	      	data: {'search': 'allEquipments',
	      			'labID': $("#labID").val()},
	      	dataType: 'json',
	      	success: function(data){
	      		$("#searchEquipment").autocomplete({
	      			source: data,
	                 //if empty results
	                  response: function(event, ui) {
	                      if (ui.content.length === 0) {
	                         var noResult = { value:"",label:"No results found" };
                			  ui.content.push(noResult);
	                      } else {
	                         // $("#empty-message").empty();
	                      }
	                  },
	                  select: function(event, ui) {
	                  	var thisEquipment = ui.item.value.split(" - ");
	                  	$.ajax({
	                  		url: "<?php echo site_url('Equipment/searchEquipment');?>",
	                  		type: 'POST',
	                  		data: {'equipmentSerialNum': thisEquipment[0],
	                  		'equipmentName': thisEquipment[1]
	                  	},
	                  	success: function(data){
	                  		console.log(data);
	                  		var searchResult = "<tr>";
	                  		searchResult += "<td>"+data[0].eqpSerialNum+"</td>";
	                  		searchResult += "<td>"+data[0].eqpName+"</td>";
	                  		searchResult += '<td><div class="btn-group"><a class="btn btn-primary" onclick = "editEquipment(\''+data[0].eqpSerialNum+'\')" rel="tooltip" title="Edit"><i class="icon_pencil"></i></a><a class="btn btn-success" data-target="#vehModal" data-toggle="modal" rel="tooltip" onclick = "viewEquipmentHistory(\''+data[0].eqpSerialNum+'\', \''+data[0].eqpName+'\')" id="'+data[0].eqpSerialNum+'"  value="'+data[0].eqpSerialNum+'" title="View Equipment History"><i class=" icon_search-2" ></i></a></div><input type="checkbox" class="check" name="checkItem"></td>';
	                  		searchResult += "</tr>";
	                  		$("#labEquipmentsTable tbody").html(searchResult);
	                  	}
	                  });
	                  }
	              });
	      	}
	      });

	      $("#searchEquipment").keyup(function(){
	      	if('' == $("#searchEquipment").val()){
	      		var url = "<?php echo site_url('Equipment/getAllEquipments/');?>";
	      		var source = url+$("#labID").val();
	      		$.ajax({
	      			url: source,
	      			type: 'GET',
	      			dataType: 'json',
	      			success: function(data){
	      				if(data[1].length != 0){
	      					var searchResult = "";
		      				for(var i = 0; i < data[1].length; i++){
		      					searchResult += "<tr>";
		      					searchResult += "<td>"+data[1][i].eqpSerialNum+"</td>";
		      					searchResult += "<td>"+data[1][i].eqpName+"</td>";
		      					searchResult += '<td><div class="btn-group"><a class="btn btn-primary" onclick = "editEquipment(\''+data[1][i].eqpSerialNum+'\')" rel="tooltip" title="Edit"><i class="icon_pencil"></i></a><a class="btn btn-success" data-target="#vehModal" data-toggle="modal" rel="tooltip" onclick = "viewEquipmentHistory(\''+data[1][i].eqpSerialNum+'\', \''+data[1][i].eqpName+'\')"  value="'+data[1][i].eqpSerialNum+'" title="View Equipment History"><i class=" icon_search-2" ></i></a></div><input type="checkbox" class="check" name="checkItem"></td>';
		      					searchResult += "</tr>";
		      				}
		      				$("#labEquipmentsTable tbody").html(searchResult);
	      				}
	      			}
	      		});  
	      	}else{}
	      }); 
	        //  END Search Equipment module

	        //File Damaged Equipment Module
	        $.ajax({
	        	url: "<?php echo site_url('Equipment/getEquipments');?>",
	        	type: 'POST',
	        	data: {'search': 'undamagedEquipments',
	        			'labID': $("#labID").val()},
	        	dataType: 'json',
	        	success: function(data){
	        		$("#searchDamaged").autocomplete({          
	        			source: data,
	                 //if empty results
	                  response: function(event, ui) {
	                      if (ui.content.length === 0) {
	                         var noResult = { value:"",label:"No results found" };
                			  ui.content.push(noResult);
	                      } else {
	                         // $("#empty-message").empty();
	                      }
	                  },
	                  select: function(event, ui) {
	                  	var thisEquipment = ui.item.value.split(" - ");
	                  	$.ajax({
	                  		url: "<?php echo site_url('Equipment/searchEquipment');?>",
	                  		type: 'POST',
	                  		data: {'equipmentSerialNum': thisEquipment[0],
	                  		'equipmentName': thisEquipment[1]
	                  	},
	                  	success: function(data){
	                  		console.log(data);
	                  		var equipList = "";
	                  		if(data.length > 0){
	                  			for(var i = 0; i < data.length; i++){
	                  				equipList += "<tr>";
	                  				equipList += "<td>"+data[i].eqpSerialNum+" "+data[i].eqpName+"</td>";
	                  				equipList += "<td><input type='checkbox' class='boxCheckDamage' onclick='checkDamage(this, "+data[i].eqpSerialNum+")' id='"+data[i].eqpSerialNum+"' value='"+data[i].eqpSerialNum+'-'+data[i].eqpName+'-'+data[i].price+"'></td>";
	                  				equipList += "</tr>";  
	                  			}
	                  			$(".damageItem").css('display', 'none');
	                  			$("#damagedList").html(equipList);

	                  			if(damagedEquipmentsArray.length != 0){
	        						for(var j = 0; j < damagedEquipmentsArray.length; j++){
	        							for(var k = 0; k < $("#damagedList .boxCheckDamage").length; k++){
	        								if($("#damagedList .boxCheckDamage")[k].id == damagedEquipmentsArray[j]){
	        									$("#damagedList #"+damagedEquipmentsArray[j]).prop('checked', true);
	        								}
	        							}
	        						}
	        					}
	                  		}
	                  	}
	                  });
	                  }
	              });
	        	}
	        });

	        $("#searchDamaged").keyup(function(){
	        	if('' == $(this).val()){
	        		$.ajax({
	        			url:"<?php echo site_url('Equipment/getDamageEquipments');?>",
	        			data: {'labID': $("#labID").val()},
	        			type: 'POST',
	        			dataType: 'json',
	        			success: function(data){
	        				var equipList = "";
	        				if(data.length > 0){
	        					for(var i = 0; i < data.length; i++){
	        						equipList += "<tr>";
	        						equipList += "<td>"+data[i].eqpSerialNum+" "+data[i].eqpName+"</td>";
	        						equipList += "<td><input type='checkbox' class='boxCheckDamage' onclick='checkDamage(this, "+data[i].eqpSerialNum+")' id='"+data[i].eqpSerialNum+"' value='"+data[i].eqpSerialNum+'-'+data[i].eqpName+'-'+data[i].price+"'></td>";
	        						equipList += "</tr>";  
	        					}
	        					$(".damageItem").css('display', 'block');
	        					$("#damagedEquipList .damageItem").attr('checked', false);
	        					$("#damagedList").html(equipList);
	        					
	        					if(damagedEquipmentsArray.length != 0){
	        						for(var j = 0; j < damagedEquipmentsArray.length; j++){
	        							for(var k = 0; k < $("#damagedList .boxCheckDamage").length; k++){
	        								if($("#damagedList .boxCheckDamage")[k].id == damagedEquipmentsArray[j]){
	        									$("#damagedList #"+damagedEquipmentsArray[j]).prop('checked', true);
	        								}
	        							}
	        						}
	        					}
	        				}
	        			}
	        		});  
	        	}else{}
	        }); 


	        $.ajax({
	        	url: "<?php echo site_url('Equipment/getDamageEquipments');?>",
	        	data: {'labID': $("#labID").val()},
	        	type: 'POST',
	        	success: function(data){
	        		console.log(data);
	        		var equipList = "";
	        		if(data.length > 0){
	        			for(var i = 0; i < data.length; i++){
	        				equipList += "<tr>";
	        				equipList += "<td>"+data[i].eqpSerialNum+" "+data[i].eqpName+"</td>";
	        				equipList += "<td><input type='checkbox' class='boxCheckDamage' onclick='checkDamage(this, "+data[i].eqpSerialNum+")' id='"+data[i].eqpSerialNum+"' value='"+data[i].eqpSerialNum+'-'+data[i].eqpName+'-'+data[i].price+"'></td>";
	        				equipList += "</tr>";  
	        			}
	        			$("#damagedList").html(equipList);
	        			$("#price").html(0);
	        			equipListLoad = equipList;
	        		}else{
	        			$("#damagemModalHeader").css('display', 'none');
	        		}
	        	}
	        });      

	        $("#damageBtn").click(function(e){
	        	if($("#damagerID").val() != '' && $("#damagerName").val() != '' && $("#damagerTeacher").val() != ''  && ($(".nameValidate").text() == '' && $(".teacherValidate").text() == '')){
	        		var items = document.getElementById("damagedEquipments").getElementsByClassName('damagedListClass'); 
			        var equipments = [];
			        $(this).unbind('submit').submit();
			        var damagedEqps = '';
			        for(var i = 0; i < items.length; i++){
			        	console.log(items[i].id.replace('id', ''));
			        	equipments.push(items[i].id.replace('id', ''));
			        	//console.log($("#damagedEquipments tr td:first-child")[i].textContent);
			        	damagedEqps += '<span style="font-weight: bold;" >'+$("#damagedEquipments tr td:first-child")[i].textContent+'</span><br>';
			        }
			        if(equipments.length != 0){  
			        	e.preventDefault();
			        	$.ajax({
						url: "<?php echo site_url('Student/checkIDNum');?>",
						type: 'POST',
						data: {'studentID': $("#damagerID").val()},
						success: function(data){
							if(0 == data.length){
								if (confirm('A new student data will be added. Are you sure you want to add this?')) {
									console.log('insert new student record');
									$.ajax({
							       		url: "<?php echo site_url('Student/addDamage');?>",
						    	   		type: 'POST',
							       		data: {'damagerID': $("#damagerID").val(),
							        		'damagerName': $("#damagerName").val()
								       	},
								       	success: function(data){}
								    });
								}
							}
					       		$.ajax({
					       			url: "<?php echo site_url('DamageList/addDamageEquipments');?>",
					       			type: 'POST',
					       			data: {
					       				'damagerID': $("#damagerID").val(),
					       				'equipment': equipments,
					       				'lab': $("#labID").val(),
					       				'damagerTeacher': $("#damagerTeacher").val()
					       			},
					       			success: function(data){
					       				$("#damageModal").modal('hide');
								  		$("#notifyModal").modal('show');
								  		$("#notifyModal .notifyHeader").html('Successfully Filed as Damage');
										$("#notifyModal #divContent").html(damagedEqps);  
										$("#notifyModal").on('hidden.bs.modal', function (e) {
											location.reload();
										});
					       			}
					       		}); 
				       		}
				       	});
			        }else{
				       	alert('Choose equipment(s)');
				       	e.preventDefault();
				    }   					
	        	}else if($(".nameValidate").text() != '' || $(".teacherValidate").text() != ''){
	        		alert('Something went wrong. Check inputs.');
				    e.preventDefault();
	        	}
		    });
			// END Damage Equipment Module         

			//Borrow Equipment Module
			$.ajax({
	        	url: "<?php echo site_url('Equipment/getEquipments');?>",
	        	type: 'POST',
	        	data: {'search': 'unborrowedEquipments',
	        			'labID': $("#labID").val()},
	        	dataType: 'json',
	        	success: function(data){
	        		$("#searchBorrowed").autocomplete({          
	        			source: data,
	                 //if empty results
	                  response: function(event, ui) {
	                      if (ui.content.length === 0) {
	                         var noResult = { value:"",label:"No results found" };
                			  ui.content.push(noResult);
	                      } else {
	                         // $("#empty-message").empty();
	                      }
	                  },
	                  select: function(event, ui) {
	                  	var thisEquipment = ui.item.value.split(" - ");
	                  	$.ajax({
	                  		url: "<?php echo site_url('Equipment/searchEquipment');?>",
	                  		type: 'POST',
	                  		data: {'equipmentSerialNum': thisEquipment[0],
	                  		'equipmentName': thisEquipment[1]
	                  	},
	                  	success: function(data){
	                  		console.log(data);
	                  		var equipList = "";
	                  		if(data.length > 0){
	                  			for(var i = 0; i < data.length; i++){
	                  				equipList += "<tr>";
	                  				equipList += "<td>"+data[i].eqpSerialNum+" "+data[i].eqpName+"</td>";
	                  				equipList += "<td><input type='checkbox' class='boxCheck' onclick='checkBorrow(this, "+data[i].eqpSerialNum+")' id='"+data[i].eqpSerialNum+"' value='"+data[i].eqpSerialNum+'-'+data[i].eqpName+'-'+data[i].price+"'></td>";
	                  				equipList += "</tr>";  
	                  			}
	                  			$(".returnItem").css('display', 'none');
	                  			$("#borrowedList").html(equipList);

	                  			if(borrowedEquipmentsArray.length != 0){
	        						for(var j = 0; j < borrowedEquipmentsArray.length; j++){
	        							for(var k = 0; k < $("#borrowedList .boxCheck").length; k++){
	        								if($("#borrowedList .boxCheck")[k].id == borrowedEquipmentsArray[j]){
	        									$("#borrowedList #"+borrowedEquipmentsArray[j]).prop('checked', true);
	        									console.log($(".boxCheck")[k]);
	        								}
	        							}
	        						}
	        					}
	                  		}
	                  	}
	                  });
	                  }
	              });
	        	}
	        });

			$("#searchBorrowed").keyup(function(){
				console.log($(this).val());
	        	if('' == $(this).val()){
	        		$.ajax({
	        			url:"<?php echo site_url('Equipment/getBorrowEquipments');?>",
	        			data: {'labID': $("#labID").val()},	
	        			type: 'POST',
	        			dataType: 'json',
	        			success: function(data){
	        				console.log(borrowedEquipmentsArray);
	        				var equipList = "";
	        				if(data.length > 0){
	        					for(var i = 0; i < data.length; i++){
	        						equipList += "<tr>";
	        						equipList += "<td>"+data[i].eqpSerialNum+" "+data[i].eqpName+"</td>";
	        						equipList += "<td><input type='checkbox' class='boxCheck' onclick='checkBorrow(this, "+data[i].eqpSerialNum+")' id='"+data[i].eqpSerialNum+"' value='"+data[i].eqpSerialNum+'-'+data[i].eqpName+'-'+data[i].price+"'></td>";
	        						equipList += "</tr>";  
	        					}
	        					$(".returnItem").css('display', 'block');
	        					$("#borrowedEquipList .returnItem").attr('checked', false);
	        					$("#borrowedList").html(equipList);

	        					if(borrowedEquipmentsArray.length != 0){
	        						for(var j = 0; j < borrowedEquipmentsArray.length; j++){
	        							for(var k = 0; k < $("#borrowedList .boxCheck").length; k++){
	        								if($("#borrowedList .boxCheck")[k].id == borrowedEquipmentsArray[j]){
	        									$("#borrowedList #"+borrowedEquipmentsArray[j]).prop('checked', true);
	        									console.log($(".boxCheck")[k]);
	        								}
	        							}
	        						}
	        					}
	        				}
	        			}
	        		});  
	        	}else{}
	        }); 

			$.ajax({
	        	url: "<?php echo site_url('Equipment/getBorrowEquipments');?>",
	        	data: {'labID': $("#labID").val()},
	        	type: 'POST',
	        	success: function(data){
	        		console.log(data);
	        		var equipList = "";
	        		if(data.length > 0){
	        			for(var i = 0; i < data.length; i++){
	        				equipList += "<tr>";
	        				equipList += "<td>"+data[i].eqpSerialNum+" "+data[i].eqpName+"</td>";
	        				equipList += "<td><input type='checkbox' class='boxCheck' onclick='checkBorrow(this, "+data[i].eqpSerialNum+")' id='"+data[i].eqpSerialNum+"' value='"+data[i].eqpSerialNum+'-'+data[i].eqpName+'-'+data[i].price+"'></td>";
	        				equipList += "</tr>";  
	        			}
	        			$("#borrowedList").html(equipList);
	        			borrowedEquipListLoad = equipList;
	        		}else{
	        			$("#borrowmModalHeader").css('display', 'none');
	        		}
	        	}
	        });   

	        $("#borrowBtn").click(function(e){
	        	if($("#borrowerID").val() != '' && $("#borrowerName").val() != '' && $("#borrowerTeacher").val() != '' && $("#incharge").val() != '' && ($(".nameValidate").text() == '' && $(".teacherValidate").text() == '' && $(".inchargeValidate").text() == '')){
	        		$(this).unbind('submit').submit();
	        		var items = document.getElementById("borrowedEquipments").getElementsByClassName('borrowedListClass'); 
			        var equipments = [];
			        var borrowedEqps = '';
			        for(var i = 0; i < items.length; i++){
			        	console.log(items[i].id.replace('id', ''));
			        	equipments.push(items[i].id.replace('id', ''));
			        	console.log($("#borrowedEquipments td")[i].textContent);
			        	borrowedEqps += '<span style="font-weight: bold;" >'+$("#borrowedEquipments td")[i].textContent+'</span><br>';
			        }
			        if(equipments.length != 0){  
			        	e.preventDefault();
			        	$.ajax({
						url: "<?php echo site_url('Student/checkIDNum');?>",
						type: 'POST',
						data: {'studentID': $("#borrowerID").val()},
						success: function(data){
							if(0 == data.length){
								if (confirm('A new student data will be added. Are you sure you want to add this?')) {
									console.log('insert new student record');
									$.ajax({
							       		url: "<?php echo site_url('Student/addBorrower');?>",
						    	   		type: 'POST',
							       		data: {'bidnum': $("#borrowerID").val(),
								        		'bname': $("#borrowerName").val()
								        		},
								       	success: function(data){}
								    });
								}
							}
								$.ajax({
				       				url: "<?php echo site_url('BorrowList/addBorrowedEquipments');?>",
				       				type: 'POST',
				       				data: {
				       					'borrowerID': $("#borrowerID").val(),
				       					'equipment': equipments,
				       					'lab': $("#labID").val(),
				       					'bteacher': $("#borrowerTeacher").val(),
				       					'incharge': $("#incharge").val()
				       				},
				       				success: function(data){
				       					$("#borrowModal").modal('hide');
							       		$("#notifyModal").modal('show');
							       		$("#notifyModal .notifyHeader").html('Equipment(s) Successfully Borrowed');
										$("#notifyModal #divContent").html(borrowedEqps);  
										$("#notifyModal").on('hidden.bs.modal', function (e) {
											location.reload();
										});
				       				}
				       			});  
							}
						});	
			        }else{
				    	alert('Choose equipment(s)');
				    	e.preventDefault();
				    }   
	        	}else if($(".nameValidate").text() != '' || $(".teacherValidate").text() != '' || $(".inchargeValidate").text() != ''){
	        		alert('Something went wrong. Check inputs.');
				    e.preventDefault();
	        	}
			});
	        // END Borrow Equipment Module

	        // Return Equipment Module
	        $("#returnerID").bind('keyup mouseup',function(){
	        	//alert($("#labID").val());
	        	if($(this).val().length > 0 && $(this).val().length < 8){
	        		$("#returnerName").val('');
	        		$("#returnedEquipments").html('<tr><td>Validating ID number...</td><td></td></tr>');
	        		$(".idNumValidate").text('Field must be 8 characters.');
		    		$('.idNumCheck').removeClass("fa fa-check");
		    		$('.nameCheck').removeClass("fa fa-check");
	        	}else if($(this).val().length > 8){
	        		$("#returnerName").val('');
	        		$("#returnedEquipments").html('<tr><td>Validating ID number...</td><td></td></tr>');
	        		$(".idNumValidate").text('Field length too long.');
		    		$('.idNumCheck').removeClass("fa fa-check");
		    		$('.nameCheck').removeClass("fa fa-check");
	        	}else if(0 == $(this).val().length){
	        		$("#returnedEquipments").html('<tr><td>No Records to display...</td><td></td></tr>');
	        		$(".idNumValidate").text('');
		    		$('.idNumCheck').removeClass("fa fa-check");
		    		$('.nameCheck').removeClass("fa fa-check");
	        	}else{
	        		$(".idNumValidate").text('');
	        		$('.idNumCheck').addClass("fa fa-check");
					$("#returnedEquipments").html('<span id="loadSpinner" style="margin-left: 220px;"><i class="fa fa-spinner fa-spin fa-5x fa-fw"></i></span><br><span style="margin-left: 170px;">Checking borrowed equipments...</span>');
					$.ajax({
	        			url:"<?php echo site_url('BorrowList/getBorrowedEquipments');?>",
	        			type: 'POST',
	        			data: {'borrower': $(this).val(),
	        					'labID': $("#labID").val()},
	        			dataType: 'json',
	        			success: function(data){
	        				if(data[0].length != 0){
		        				$("#returnerName").val(data[0][0].studentName);
		        				$('.nameCheck').addClass("fa fa-check");

		        				if(data[1].length != 0){
		        					$("#returnModalHeader").html(	
		        					 '<th style="padding-right: 140px;">All Equipments</th><th style="padding-right: 147px;">Borrowed Date</th><th><input type="checkbox" class="returnAll" onclick = "checkAllReturn()"></th>');
		        					var returnEqp = '';
		        					for(var i = 0; i < data[1].length; i++){
		        						returnEqp += "<tr id='"+data[1][i].eqpSerialNum+"'>";
		        						returnEqp += "<td style='padding-right: 60px;'>"+data[1][i].eqpSerialNum+" - "+data[1][i].eqpName+"</td>";
		        						returnEqp += "<td style='padding-left: 70px; padding-right: 130px;'>"+data[1][i].borrowedDate+"</td>";
		        						returnEqp += "<td><input type='checkbox' class='returnBoxCheck' onclick='clearReturnAll()' id='"+data[1][i].eqpSerialNum+"' value='"+data[1][i].eqpSerialNum+"'></td>";
		        						returnEqp += "</tr>";
		        					}
		        					$("#returnedEquipments").html(returnEqp);	
		        				}else{
					        		$("#returnedEquipments").html('<tr><td>No Borrowed Equipment(s)...</td><td></td></tr>');
		        				}
	        				}else{
	        					$("#returnerName").val('');
	        					$('.nameCheck').removeClass("fa fa-check");
	        					$("#returnedEquipments").html('<tr><td>No Records to Display...</td><td></td></tr>');
	        				}
	        			}
	        		});
	        	}
	        });

			$("#returnBtn").click(function(){
				var returnItems = document.getElementById('returnedEquipments').getElementsByClassName('returnBoxCheck');
				var returnItemsArray = [];
				var returnEqps = '';
				for(var i = 0; i < returnItems.length; i++){
					if($("#returnedEquipments #"+returnItems[i].id).is(':checked')){
						//console.log($("#returnedEquipments #"+returnItems[i].id+" td")[0].textContent);  
						returnItemsArray.push(returnItems[i].id);
						returnEqps += '<span style="font-weight: bold;">'+$("#returnedEquipments #"+returnItems[i].id+" td")[0].textContent+'</span><br>';
					}
				}
				if(returnItemsArray.length != 0){
					$.ajax({
			       		url: "<?php echo site_url('BorrowList/returnEquipments');?>",
			       		type: 'POST',
			       		data: {'equipment': returnItemsArray},
			       		success: function(data){
			       			$("#returnModal").modal('hide');
							$("#notifyModal").modal('show');
							$("#notifyModal .notifyHeader").html('Successfully Returned Equipment(s)');
							$("#notifyModal #divContent").html(returnEqps);  
							$("#notifyModal").on('hidden.bs.modal', function (e) {
								location.reload();
							});
			       		}
			       	});  
				}else{
					alert('Choose equipment(s)...');
				}
			});
	        //END added codes by JV 
		});

		//added by JV
		var totalPrice = 0;

		// modal reset module
		$(document).on('hidden.bs.modal', function (e) {
			$(".idNumValidate, .nameValidate, .teacherValidate, .inchargeValidate").text('');

			// damage modal reset
		    $("#damageModal").find("input,textarea,select").val('');
		    $('input[class=boxCheckDamage]').prop('checked', false);
		    $("#damagedEquipList .damageItem").attr('checked', false);
		    if(equipListLoad.length != 0){
	        	$("#damagedList").html(equipListLoad);
	        }
	        $("#damagedEquipments").html('');
	        $("#price").html(0);
	        totalPrice = 0;
	        damagedEquipmentsArray = [];
	        $('.idNumCheck').removeClass("fa fa-check");
	    	$('.nameCheck').removeClass("fa fa-check");
			$('.teacherCheck').removeClass("fa fa-check");
			$("#damagerName").attr('disabled', false);
			$("#damagerTeacher").attr('disabled', false);

	        // borrow modal reset
	        $("#borrowModal").find("input,textarea,select").val('');
	        $('input[class=boxCheck]').prop('checked', false);
	        $("#borrowedEquipList .returnItem").attr('checked', false);
	        if(borrowedEquipListLoad.length != 0){
	        	$("#borrowedList").html(borrowedEquipListLoad);
	        }
	        $("#borrowedEquipments").html('');
	        $("#borrowedPrice").html(0);
	        borrowedEquipmentsArray = [];
			$('.idNumCheck').removeClass("fa fa-check");
	    	$('.nameCheck').removeClass("fa fa-check");
			$('.teacherCheck').removeClass("fa fa-check");
			$("#borrowerName").attr('disabled', false);
			$("#borrowerTeacher").attr('disabled', false);

	        // return modal reset
	         $("#returnModal").find("input,textarea,select").val('');
	         $('input[class=returnBoxCheck]').prop('checked', false);
		     $("#returnedEquipments .returnAll").attr('checked', false);
		     $("#returnedEquipments").html('<tr><td>No Records to display...</td><td></td></tr>');
		     $('.idNumCheck').removeClass("fa fa-check");
	    	 $('.nameCheck').removeClass("fa fa-check");

		     // edit equipment modal reset
	         $("#editModal").find("input,textarea,select").val('');
		});

		function checkDamage(thisValue, thisID){
			console.log('checkDamage');
			var getValue = thisValue.value.split("-");
			var id = thisID+"id";
			console.log(getValue[0]+" "+thisID);
			if($("#damagedEquipList #"+thisID).is(':checked')){
				damagedEquipmentsArray.push(thisID);
				var newDamage = "<tr id ="+id+" class='damagedListClass'>";
					newDamage +="<td style='width: 100%'>"+getValue[0]+" "+getValue[1]+"</td>";
					newDamage +="<td>"+getValue[2]+"</td>";
					newDamage +="</td>";
				totalPrice += parseInt(getValue[2]);    

				$("#damagedEquipments").append(newDamage);
			}
			else{
				damagedEquipmentsArray.splice(damagedEquipmentsArray.indexOf(thisID), 1);
				if($("#damagedEquipList .returnItem").is(':checked')){
					$("#damagedEquipList .returnItem").prop('checked', false);
				}
				$("#"+id).remove();
				totalPrice -= parseInt(getValue[2]);
			}
			$("#price").html(totalPrice);
		}

		function checkBorrow(thisValue, thisID){
			var getValue = thisValue.value.split("-");
			var id = getValue[0]+"id";
			console.log(getValue[0]+" "+thisID);
			if($("#borrowedEquipList #"+getValue[0]).is(':checked')){
				borrowedEquipmentsArray.push(thisID);
				console.log(borrowedEquipmentsArray);
				var newDamage = "<tr id ="+id+" class='borrowedListClass'>";
					newDamage +="<td style='width: 100%'>"+getValue[0]+" "+getValue[1]+"</td>";
					newDamage +="</td>";
				
				$("#borrowedEquipments").append(newDamage);
			}
			else{
				console.log(borrowedEquipmentsArray.indexOf(thisID));
				borrowedEquipmentsArray.splice(borrowedEquipmentsArray.indexOf(thisID), 1);
				console.log(borrowedEquipmentsArray);
				if($("#borrowedEquipList .returnItem").is(':checked')){
					$("#borrowedEquipList .returnItem").prop('checked', false);
				  }
				$("#"+id).remove();
				}
			
		}

		function checkAllDamage(){
			var table = document.getElementById('damagedList');
			var items = table.getElementsByClassName('boxCheckDamage');

			if($("#damagedEquipList .damageItem").is(':checked')){
				var list = document.getElementById('damagedEquipments').getElementsByClassName('damagedListClass');
				console.log('length', list.length);
				for (var i = 0; i < items.length; i++) {
					var item = items[i].value.split("-");
					var id = item[0]+"id";
					var checkExists = false;
					damagedEquipmentsArray.push(parseInt(item[0]));
					if(list.length != 0){
						for(var j = 0; j < list.length; j++){
							if(id == list[j].id){
								checkExists = true;
								break;
							}
						}
						if(false == checkExists){
							var newDamage = "<tr id ="+id+" class='damagedListClass'>";
							newDamage +="<td style='width: 100%'>"+item[0]+" "+item[1]+"</td>";
							newDamage +="<td>"+item[2]+"</td>";
							newDamage +="</td>";

							$("#damagedEquipments").append(newDamage);
							totalPrice += parseInt(item[2]);
						}
					}else{
						var newDamage = "<tr id ="+id+" class='damagedListClass'>";
						newDamage +="<td style='width: 100%'>"+item[0]+" "+item[1]+"</td>";
						newDamage +="<td>"+item[2]+"</td>";
						newDamage +="</td>";

						$("#damagedEquipments").append(newDamage);
						totalPrice += parseInt(item[2]);    
					}
					$(".boxCheckDamage").prop('checked', true);
				}
			}
			else{
				damagedEquipmentsArray = [];
				for (var i = 0; i < items.length; i++) {
					$(".boxCheckDamage").prop('checked', false);
				}
				$("#damagedEquipments").html('');
				totalPrice = 0;
			}
			$("#price").html(totalPrice);
		}

		function checkAllBorrow(){
			var table = document.getElementById('borrowedList');
			var items = table.getElementsByClassName('boxCheck');

			if($("#borrowedEquipList .returnItem").is(':checked')){
				var list = document.getElementById('borrowedEquipments').getElementsByClassName('borrowedListClass');
				console.log('length', list.length);
				for (var i = 0; i < items.length; i++) {
					var item = items[i].value.split("-");
					var id = item[0]+"id";
					var checkExists = false;
					borrowedEquipmentsArray.push(parseInt(item[0]));
					console.log(borrowedEquipmentsArray);
					if(list.length != 0){
						for(var j = 0; j < list.length; j++){
							if(id == list[j].id){
								checkExists = true;
								break;
							}
						}
						if(false == checkExists){
							var newDamage = "<tr id ="+id+" class='borrowedListClass'>";
							newDamage +="<td style='width: 100%'>"+item[0]+" "+item[1]+"</td>";
							newDamage +="</td>";

							$("#borrowedEquipments").append(newDamage);
							totalPrice += parseInt(item[2]);
						}
					}else{
						var newDamage = "<tr id ="+id+" class='borrowedListClass'>";
						newDamage +="<td style='width: 100%'>"+item[0]+" "+item[1]+"</td>";
						newDamage +="</td>";

						$("#borrowedEquipments").append(newDamage);  
					}
					$(".boxCheck").prop('checked', true);
				}
			}
			else{
				borrowedEquipmentsArray = [];
				for (var i = 0; i < items.length; i++) {
					$(".boxCheck").prop('checked', false);
				}
				$("#borrowedEquipments").html('');
				
			}
			$("#borrowedPrice").html(totalPrice);
		}
			
		function viewEquipmentHistory(viewThisEquipment, thisName){
			$("#vehModal").modal('show');
			$("#vehModal .modal-title").html(viewThisEquipment+" - "+thisName);
			$.ajax({
				url: "<?php echo site_url('Equipment/getEquipmentHistory');?>",
				type: 'POST',
				data: {'equipmentSerialNum': viewThisEquipment},
				success: function(data){
					console.log('equipmentHistory', data);
					var history = '';
					if(data[0].length != 0){
						for(var i = 0; i < data[0].length; i++){
							history += "<tr>";
							history += "<td>"+data[0][i].dateReported+"</td>";
							history += "<td>Filed as damage by:<br>Student ID: "+data[0][i].studentID+"<br>Student Name: "+data[0][i].studentName+"</td>";
							history += "</tr>";
						}
					}
					if(data[1].length != 0){
						for(var i = 0; i < data[1].length; i++){
							history += "<tr>";
							history += "<td>"+data[1][i].borrowedDate+"</td>";
							history += "<td>Borrowed by:<br>Student ID: "+data[1][i].studentID+"<br>Student Name: "+data[1][i].studentName+"</td>";
							history += "</tr>";
						}
					}
					$("#equipmentHistory").html(history);
					if(data[0].length == 0 && data[1].length == 0){
						$("#equipmentHistory").html("<tr><td>No records to display...</td><td></td></tr>");
					}
				}
			});  
			$("#equipmentHistory").html('<tr><td><span id="loadSpinner" style="margin-left: 220px;"><i class="fa fa-spinner fa-spin fa-5x fa-fw"></i></span></td></tr>');
		}

		function editEquipment(editThisEquipment){
			$("#editModal").modal('show');
			$.ajax({
				url: "<?php echo site_url('Equipment/getEquipmentDetails');?>",
				type: 'POST',
				data: {'equipmentSerialNum': editThisEquipment},
				success: function(data){
					if(data.length != 0){
						console.log(data[0].eqpSerialNum);
						$("#editSerialNum").val(data[0].eqpSerialNum);
						$("#editName").val(data[0].eqpName);
						$("#editPrice").val(data[0].price);
					}
				}
			});  
		}

		function checkAllReturn(){
			var table = document.getElementById('returnedEquipments');
			var items = table.getElementsByClassName('returnBoxCheck');

			if($("#returnModalHeader .returnAll").is(':checked')){
				for(var i = 0; i < items.length; i++){
					$(".returnBoxCheck").prop('checked', true);
				}
			}else{
				for(var i = 0; i < items.length; i++){
					$(".returnBoxCheck").prop('checked', false);
				}
			}
		}

		function clearReturnAll(){
			if($("#returnModalHeader .returnAll").is(':checked')){
				$("#returnModalHeader .returnAll").prop('checked', false);
			}
		}

		// ID number checker module
	    function checkIDnumber(thisID){
	    	console.log(thisID);
	    	if(thisID.length == 8){
	    		$(".idNumValidate").text('');
	    		$('.idNumCheck').addClass("fa fa-check");
	    		$.ajax({
				url: "<?php echo site_url('Student/checkIDNum');?>",
				type: 'POST',
				data: {'studentID': thisID},
					success: function(data){
						if(data.length != 0){
						    // console.log(data);
						    $("#damagerName, #borrowerName").attr('disabled', true);
							$("#damagerName, #borrowerName").val(data[0].studentName);

							$('.nameCheck').addClass("fa fa-check");
						}else{
							$("#damagerName, #borrowerName").attr('disabled', false);
							$("#damagerName, #borrowerName").val('');

							$('.nameCheck').removeClass("fa fa-check");
						}
					}
				});  
	    	}else if(thisID.length == 0){
	    		$(".idNumValidate").text('');
	    		$('.idNumCheck').removeClass("fa fa-check");
	    		$('.nameCheck').removeClass("fa fa-check");
	    	}else if(thisID.length > 8){
	    		$(".idNumValidate").text('Field length too long.');
	    		$('.idNumCheck').removeClass("fa fa-check");
	    		$('.nameCheck').removeClass("fa fa-check");

				$("#damagerName, #borrowerName").attr('disabled', false);
				$("#damagerName, #borrowerName").val('');

	    	}else{
	    		$(".idNumValidate").text('Field must be 8 characters.');
	    		$('.idNumCheck').removeClass("fa fa-check");
	    		$('.nameCheck').removeClass("fa fa-check");

	    		$("#damagerName, #borrowerName").attr('disabled', false);
				$("#damagerName, #borrowerName").val('');
	    	}
	    }
	    	
	    // END ID number checker module


	    function validate(validateThis, event){
	    	if(event.keyCode != 9){
	    		var check =  /^[a-zA-Z ]*$/.test(validateThis.value);
		    	console.log(check);
		    	if(false == check){
		    		switch(validateThis.id){
	  					case "borrowerName":
	  					case "damagerName": $(".nameValidate").text('Invalid character(s).');
	  										$('.nameCheck').removeClass("fa fa-check"); break;

	  					case "borrowerTeacher": 
	  					case "damagerTeacher": $(".teacherValidate").text('Invalid character(s).');
	  										   $('.teacherCheck').removeClass("fa fa-check"); break;

	  					case "incharge": $(".inchargeValidate").text('Invalid character(s).');
	  									 $('.inchargeCheck').removeClass("fa fa-check"); break;
		  			}
		    	}else{
		    		console.log('value', validateThis.value);
		    			switch(validateThis.id){
		  					case "borrowerName":
		  					case "damagerName": $(".nameValidate").text('');
		  										(validateThis.value != '')? $('.nameCheck').addClass("fa fa-check"):  $('.nameCheck').removeClass("fa fa-check"); break;
		  					case "borrowerTeacher": 
		  					case "damagerTeacher": $(".teacherValidate").text('');
		  										   (validateThis.value != '')? $('.teacherCheck').addClass("fa fa-check"): $('.teacherCheck').removeClass("fa fa-check"); break;
		  										   

		  					case "incharge": $(".inchargeValidate").text('');
		  									 (validateThis.value != '')? $('.inchargeCheck').addClass("fa fa-check"): $('.inchargeCheck').removeClass("fa fa-check"); break;
			    		}
		    	}
	    	 }
	    }

	    function thisLab(labID, elementID){
	    	$(".lab").removeClass("active");
	    	$("#"+elementID).addClass("active");
			$("#all").removeClass("active");
			$("#addBtn").text("Add Equipment");
			var source = "<?php echo site_url('Index/loadIframe/lab/');?>";
			var url = source+labID;
			$("#frame").attr('src', url);
	    }

	</script>	
</head>
<body></body>
</html>	
