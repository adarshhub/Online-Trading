


</div>
<!-- Modal -->
<div class="modal fade" id="passwordChangeModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Change Password</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form>
                <input class="form-control" type="password" placeholder="Current password" required id="current-password"><br>
                <input class="form-control" type="password" placeholder="New password" required id="new-password"><br>
                <input class="form-control" type="password" placeholder="Confirm password" required id="confirm-password"><br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="changePassword()">Change</button>
                </div>
                <div id="change-password-notice">

                </div>
            </form>
        </div>
        
      </div>
      
    </div>
  </div>
<div id="notice-box">

</div>
</body>
</html>