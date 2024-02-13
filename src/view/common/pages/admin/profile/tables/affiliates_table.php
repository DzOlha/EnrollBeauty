<table id="data-table"
       class="table card-table table-striped table-vcenter text-nowrap mb-0">
    <thead>
    <tr>
        <th class="wd-lg-5p">
            <span class="arrow_column active">
                ID
                <img src="<?=ARROW_DOWN?>"
                     id="id_arrow"
                     class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Affiliates::$id?>"
                     data-order="asc">
            </span>
        </th>
        <th class="wd-lg-8p">
            <span class="arrow_column">
                Name
                <img src="" id="name_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Affiliates::$name?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-15p">
            <span class="arrow_column">
                Country
                <img src="" id="country_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Affiliates::$country?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-10p">
            <span class="arrow_column">
                City
                <img src="" id="city_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Affiliates::$city?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-15p">
            <span class="arrow_column">
                Street Address
                <img src="" id="address_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Affiliates::$address?>"
                     data-order="">
            </span>
        </th>

        <th class="wd-lg-10p">
            <span class="arrow_column">
                Manager
                <img src="" id="manager_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Affiliates::$worker_manager_id?>"
                     data-order="">
            </span>
        </th>

        <th class="wd-lg-5p">
            <span class="arrow_column">
                Created At
                <img src="" id="email_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Affiliates::$created_date?>"
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