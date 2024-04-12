<table id="data-table"
       class="table card-table table-striped table-vcenter text-nowrap mb-0">
    <thead>
    <tr>
        <th class="wd-lg-2p">
            <span class="arrow_column active">
                ID
                <img src="<?=ARROW_DOWN?>"
                     id="id_arrow"
                     class="sort_arrow"
                     data-column="<?=\Src\Model\Table\WorkersServicePricing::$id?>"
                     data-order="asc">
            </span>
        </th>
        <th class="wd-lg-35p">
            <span class="arrow_column">
                Service Name
                <img src="" id="img_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Services::$name?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-30p">
            <span class="arrow_column">
                Price
                <img src="" id="login_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\WorkersServicePricing::$price?>"
                     data-order="">
            </span>
        </th>

        <th class="wd-lg-30p">
            <span class="arrow_column">
                Last Updated
                <img src="" id="email_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\WorkersServicePricing::$updated_datetime?>"
                     data-order="">
            </span>
        </th>

        <th class="wd-lg-5p">
            <span class="arrow_column">
                Action
            </span>
        </th>
    </tr>
    </thead>
    <tbody id="table-body">
    <!--                                JS generated rows-->
    </tbody>
</table>