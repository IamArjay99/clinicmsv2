<div class="content-wrapper">

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Supply</h4>
                </div>
                <div class="card-body" id="pageContent">     
                    <div class="jumping-dots-loader my-5">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>     
                </div>
            </div>
        </div>
    </div>

</div>


<script>


    $(document).ready(function() {


        // ----- DATATABLES -----
        function initDataTables() {
            if ($.fn.DataTable.isDataTable("#tableMedicine")) {
                $("#tableMedicine").DataTable().destroy();
            }
            
            var table = $("#tableMedicine")
                .css({ "min-width": "100%" })
                .removeAttr("width")
                .DataTable({
                    proccessing:    false,
                    serverSide:     false,
                    scrollX:        true,
                    sorting:        [],
                    scrollCollapse: true,
                    columnDefs: [
                        { targets: 0, width: '50px'  },
                        { targets: 1, width: '250px' },
                        { targets: 2, width: '250px' },
                        { targets: 3, width: '250px' },
                        { targets: 4, width: '250px' },
                    ],
                });
        }
        // ----- END DATATABLES -----


        // ----- REFRESH TABLE CONTENT -----
        function refreshTableContent() {
            !document.getElementsByClassName("jumping-dots-loader").length && $("#tableContent").html(preloader);
            
            setTimeout(() => {
                let content = tableContent();
                $("#tableContent").html(content);
                initDataTables();
            }, 100);
        }
        // ----- END REFRESH TABLE CONTENT -----


        // ----- MEDICINE CONTENT -----
        function medicineContent() {

            let tbodyHTML = '';
            let data = getTableData(`medicines WHERE is_deleted = 0`);
            data.map((item, index) => {
                let {
                    medicine_id = "",
                    name        = "",
                    brand       = "",
                    quantity    = "",
                } = item;

                let maximumValue = 500;
                let ariaValue  = quantity > maximumValue ? maximumValue : quantity;
                let percentage = ariaValue / maximumValue * 100;
                    percentage = percentage.toFixed(1);

                tbodyHTML += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>
                        <div class="progress progress-lg mt-2">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: ${percentage}%" aria-valuenow="${ariaValue}" aria-valuemin="0" aria-valuemax="${maximumValue}">${percentage}%</div>
                        </div>
                    </td>
                    <td>${name}</td>
                    <td>${brand}</td>
                    <td>${quantity}</td>
                    <td>
                        <div class="text-center">
                            <button class="btn btn-outline-info btnEdit"
                                medicineID="${medicine_id}"><i class="fas fa-pencil-alt"></i></button>
                            <button class="btn btn-outline-danger btnDelete"
                                medicineID="${medicine_id}"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </td>
                </tr>`;
            });

            let html = `
            <div class="row">
                <div class="col-12" id="filterMedicine">
                    <div class="row mb-4">
                        <div class="col-md-4 col-sm-12">
                            <h4>Medicine</h4>
                        </div>
                        <div class="col-md-8 col-sm-12 text-right">
                            <button class="btn btn-primary btnAdd"
                                id="btnAdd"><i class="fas fa-plus"></i> Add Medicine</button>
                        </div>
                    </div>
                </div>
                <div class="col-12" id="tableMedicineParent">
                    <table class="table table-hover table-bordered" id="tableMedicine">
                        <thead>
                            <tr class="text-center">
                                <th>No.</th>
                                <th>Percentage</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableMedicineTbody">
                            ${tbodyHTML}
                        </tbody>
                    </table>
                </div>
            </div>`;

            return html;
        }
        // ----- END MEDICINE CONTENT -----


        // ----- PAGE CONTENT -----
        function pageContent() {
            !document.getElementsByClassName("jumping-dots-loader").length && $("#pageContent").html(preloader);

            let html = `
            <div class="card">
                <div class="card-body" id="medicineContent">${medicineContent()}</div>
            </div>
            <div class="card my-5">
                <div class="card-body" id="careEquipmentContent">${medicineContent()}</div>
            </div>`;

            setTimeout(() => {
                $("#pageContent").html(html);
                initDataTables();
            }, 100);
        }
        pageContent();
        // ----- END PAGE CONTENT -----


        // ----- FORM CONTENT -----
        function formContent(data = false, isUpdate = false) {
            let {
                measurement_id      = "",
                abbreviation = "",
                name         = "",
            } = data && data[0];

            let buttonSaveUpdate = !isUpdate ? `
            <button class="btn btn-primary" 
                id="btnSave"
                measurementID="${measurement_id}">Save</button>` : `
            <button class="btn btn-primary" 
                id="btnUpdate"
                measurementID="${measurement_id}">Update</button>`;

            let html = `
            <div class="row p-3">
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label>Name <code>*</code></label>
                        <input type="text" 
                            class="form-control validate"
                            name="name"
                            minlength="1"
                            maxlength="50"
                            value="${name}"
                            required>
                        <div class="d-block invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label>Abbreviation</label>
                        <input type="text" 
                            class="form-control validate"
                            name="abbreviation"
                            minlength="2"
                            maxlength="20"
                            value="${abbreviation}">
                        <div class="d-block invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                ${buttonSaveUpdate}
                <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>`;

            return html;
        }
        // ----- END FORM CONTENT -----


        // ----- BUTTON ADD -----
        $(document).on("click", "#btnAdd", function() {
            let html = formContent();
            $("#modal .modal-dialog").removeClass("modal-md").addClass("modal-md");
            $("#modal_content").html(html);
            $("#modal .page-title").text("ADD MEASUREMENT");
            $("#modal").modal('show');
            generateInputsID("#modal");
        });
        // ----- END BUTTON ADD -----


        // ----- BUTTON EDIT -----
        $(document).on("click", ".btnEdit", function() {
            let measurementID = $(this).attr("measurementID");
            let data = getTableData(`measurements WHERE measurement_id = ${measurementID}`);

            $("#modal .modal-dialog").removeClass("modal-md").addClass("modal-md");
            $("#modal_content").html(preloader);
            $("#modal .page-title").text("EDIT MEASUREMENT");
            $("#modal").modal('show');

            setTimeout(() => {
                let html = formContent(data, true);
                $("#modal_content").html(html);
                generateInputsID("#modal");
            }, 100);
        });
        // ----- END BUTTON EDIT -----


        // ----- BUTTON SAVE -----
        $(document).on("click", `#btnSave`, function() {
            let measurementID = $(this).attr("measurementID");
            
            let validate = validateForm("modal");
            if (validate) {
                $("#modal").modal("hide");

                let data = getFormData("modal");
                    data["tableName"] = "measurements";
                    data["feedback"]  = $(`[name="name"]`).val();
                    data["method"]    = "add";
    
                sweetAlertConfirmation("add", "Measurement", "modal", null, data, true, refreshTableContent);
            }
        })
        // ----- END BUTTON SAVE -----


        // ----- BUTTON SAVE -----
        $(document).on("click", `#btnUpdate`, function() {
            let measurementID = $(this).attr("measurementID");
            
            let validate = validateForm("modal");
            if (validate) {
                $("#modal").modal("hide");

                let data = getFormData("modal");
                    data["tableName"]   = "measurements";
                    data["feedback"]    = $(`[name="name"]`).val();
                    data["method"]      = "update";
                    data["whereFilter"] = `measurement_id=${measurementID}`;
    
                sweetAlertConfirmation("update", "Measurement", "modal", null, data, true, refreshTableContent);
            }
        })
        // ----- END BUTTON SAVE -----
        

        // ----- BUTTON DELETE -----
        $(document).on("click", `.btnDelete`, function() {
            let measurementID = $(this).attr("measurementID");

            let data = {
                tableName: 'measurements',
                tableData: {
                    is_deleted: 1
                },
                whereFilter: `measurement_id=${measurementID}`,
                feedback:    $(`[name="name"]`).val(),
                method:      "update"
            }
            sweetAlertConfirmation("delete", "Measurement", "modal", null, data, true, refreshTableContent);
        })
        // ----- END BUTTON DELETE -----

    })

</script>
    