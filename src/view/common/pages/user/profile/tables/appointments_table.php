<table id="data-table"
       class="table card-table table-striped table-vcenter text-nowrap mb-0">
    <thead>
    <tr>
        <th class="wd-lg-8p">
            <span class="arrow_column active">
                ID
                <img src="<?=ARROW_DOWN?>"
                     id="id_arrow"
                     class="sort_arrow"
                     data-column="<?=\Src\Model\Table\OrdersService::$id?>"
                     data-order="asc">
            </span>
        </th>
        <th class="wd-lg-8p">
            <span class="arrow_column">
                Service
                <img src="" id="img_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Services::$name?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-20p">
            <span class="arrow_column">
                Worker
                <img src="" id="login_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Workers::$name?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-20p">
            <span class="arrow_column">
                Affiliate Address
                <img src="" id="email_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Affiliates::$address?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-10p">
            <span class="arrow_column">
                Date
                <img src="" id="email_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\OrdersService::$start_datetime?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-2p">
            <span class="arrow_column">
                Start
                <img src="" id="email_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\OrdersService::$start_datetime?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-2p">
            <span class="arrow_column">
                End
                <img src="" id="email_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\OrdersService::$end_datetime?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-10p">
            <span class="arrow_column">
                Price
                <img src="" id="created_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\WorkersServicePricing::$price?>"
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