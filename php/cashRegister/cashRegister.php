  <div class="container-fluid" ng-controller=itemsController>
     <div class="panel panel-default">
        <div class="panel-heading">
          <p class="panel-title total-amount">Total: <span>{{total() | currency: $}}</span></p>
          <p class="panel-title item-display">Item: <span ng-hide='product==undefined'>{{product.name + " @ $" + product.price}}</span></p>
        </div>
        
        <div class="panel-body">
          <form name="cashRegister" class="form-horizontal">
            <!-- Product Selector -->
            <div class="form-group">
              <div class="col-md-4 product-inputs-left ui-widget">
                <input class="form-control input-lg" list="product_names" type="text" ng-model="inputed_product_name" ng-change="select_product()">
                  <datalist id="product_names">
                    <option ng-repeat="a in product_list" value="{{a.name}}">
                  </datalist>
                <span class="help-block help-block-label">Type item</span>
              </div>
              
              <div class="col-md-3 product-inputs">
                <div class="input-group">
                  <div class="input-group-addon" id="currencySymbol">$</div>
                  <input ng-model="product_cost" id="productUnitCost" class="form-control input-lg" type="number" readonly="true">
                </div>
                <span class="help-block help-block-label">Unit Cost</span>
              </div>
              <div class="col-md-2 product-inputs">
                <input class="form-control input-lg" type="number" ng-model="inputed_product_quantity" ng-change="select_quantity()">
                <span class="help-block help-block-label">Choose Quantity</span>
              </div>
              <div class="col-md-3 product-inputs-right">
                <button class="btn btn-lg btn-primary btn-block" id="btnAddProduct" ng-click=add_to_cart()>ADD</button>
                <span class="help-block help-block-label">Add to Cart</span>
              </div>
            </div>
          </form>
          
          <!-- Cart contents -->
          <div class="well" id="cartDisplay">
            <table  class="table table-condensed table-striped table-responsive table-borderless" ng-model=discount_row>
              <tr>
                <th>{{discount_row[discount_row.length-1].name}}</th>
                <th>{{discount_row[discount_row.length-1].discount}}</th>
               </tr>
               <tr ng-repeat="x in cart">
                <td>{{x.name + ' @ $' + x.price + ' ea'}}</td>
                <td>{{x.quantity}}</td>
                <td>{{x.quantity * x.price | currency:$}}</td>
               </tr>
             </table>
           </div>
          
          <!-- Admin Functions -->
          <hr>
          <div class="form-group">
             <div class="col-md-3">
               <button id=btnVoidLastTxn ng-click="void_last_transaction()">
                 VOID LAST TRANSACTION
               </button>
               <span class="help-block help-block-label">Void Last Transaction</span>
            </div>
            <div class="col-md-2 product-inputs-left">
              <button id="btnCancelSale" ng-click="void_sale()">
                CANCEL SALE
              </button>
              <span class="help-block help-block-label">Void Entire Transaction</span>
            </div>
            <div class="col-md-4">
              <select class="form-control input-lg" type="text" ng-model="purchasing_employee" 
                      ng-options="a.name for a in employee_list">                
              </select>
              <span class="help-block help-block-label">Select Employee</span>
            </div>
            <div class= "col-md-3">
                <button id="btnApplyEmpDiscount" ng-click="apply_discount()">
                  APPLY DISCOUNT
                </button>
                <span class="help-block help-block-label">Apply Employee Discount</span>
            </div>
          </div>
          
      </div>
 </div>
    




  
  