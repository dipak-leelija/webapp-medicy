<!-- password modal  -->
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                                Password Change
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                           <div class="form-group">
                                                                <div class="form-group ">
                                                                    <input type="password" class="form-control " id="bpassword" name="password" maxlength="12" placeholder="Current Password" value="<?=  $password; ?>" required>
                                                                </div>
                                                                <div class="form-group  ">
                                                                    <input type="password" class="form-control " id="password" name="password" maxlength="12" placeholder="Enter New Password" required>
                                                                </div>
                                                                <div class="form-group  ">
                                                                    <input type="password" class="form-control " id="cpassword" name="cpassword" maxlength="12" placeholder="Confirm Password" required>
                                                                    <small>
                                                                        <p id="cpasserror" class="text-danger" style="display: none;"></p>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary" type="submit" name="submit">Save changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- password modal end  -->