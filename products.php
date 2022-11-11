
<div class="card rounded-0 shadow">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Liste des Repas/Produits</h3>
        <div class="card-tools align-middle">
            <button class="btn btn-dark btn-sm py-1 rounded-0" type="button" id="create_new">Nouveau Repas/Produits</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="10%">
                <col width="30%">
                <col width="15%">
                <col width="10%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>
<!--                    <th class="text-center p-0">Code</th>-->
                    <th class="text-center p-0">Categories</th>
                    <th class="text-center p-0">Repas</th>
                    <th class="text-center p-0">Prix</th>
<!--                    <th class="text-center p-0">Alert stock</th>-->
                    <th class="text-center p-0">Status</th>
                    <th class="text-center p-0">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT p.*,c.name as cname FROM `product_list` p inner join `category_list` c on p.category_id = c.category_id where p.delete_flag = 0 order by `name` asc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetch_assoc()):
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
<!--                    <td class="py-0 px-1">--><?php //echo $row['product_code'] ?><!--</td>-->
                    <td class="py-0 px-1"><?php echo $row['cname'] ?></td>
                    <td class="py-0 px-1">
                        <div class="fs-6 fw-bold truncate-1" title="<?php echo $row['name'] ?>"><?php echo $row['name'] ?></div>
                        <div class="fs-6 fw-light truncate-3" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></div>
                    </td>
                    <td class="py-0 px-1 text-end"><?php echo number_format($row['price']) ?> XAF</td>
<!--                    <td class="py-0 px-1 text-end">--><?php //echo number_format($row['alert_restock']) ?><!--</td>-->
                    <td class="py-0 px-1 text-center">
                        <?php 
                        if($row['status'] == 1){
                            echo  '<span class="py-1 px-3 badge rounded-pill bg-success"><small>Active</small></span>';
                        }else{
                            echo  '<span class="py-1 px-3 badge rounded-pill bg-danger"><small>Inactive</small></span>';

                        }
                        ?>
                    </td>
                    <td class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item view_data" data-id = '<?php echo $row['product_id'] ?>' href="javascript:void(0)">Voir Détails</a></li>
                            <li><a class="dropdown-item edit_data" data-id = '<?php echo $row['product_id'] ?>' href="javascript:void(0)">Modifier</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['product_id'] ?>' data-name = '<?php echo $row['product_code']." - ".$row['name'] ?>' href="javascript:void(0)">Supprimer</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
               
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $('#create_new').click(function(){
            uni_modal('Ajouter un nouveau repas/produit',"manage_product.php",'mid-large')
        })
        $('.edit_data').click(function(){
            uni_modal('Modifier un repas/produit',"manage_product.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('.view_data').click(function(){
            uni_modal('Details repas/produits',"view_product.php?id="+$(this).attr('data-id'),'')
        })
        $('.delete_data').click(function(){
            _conf("Etes-vous sure de vouloir supprimer definitivement <b>"+$(this).attr('data-name')+"</b> de la liste de produit ?",'delete_data',[$(this).attr('data-id')])
        })
        $('table td,table th').addClass('align-middle')
        $('table').dataTable({
            columnDefs: [
                { orderable: false, targets:3 }
            ]
        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./Actions.php?a=delete_product',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                console.log(err)
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled',false)
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert("An error occurred.")
                    $('#confirm_modal button').attr('disabled',false)
                }
            }
        })
    }
</script>