<table id="data-table"
       class="table card-table table-striped table-vcenter text-nowrap mb-0">
    <thead>
    <tr>
        <th class="wd-lg-10p">
            <span class="arrow_column active">
                ID
                <img src="<?=ARROW_DOWN?>"
                     id="id_arrow"
                     class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Services::$id?>"
                     data-order="asc">
            </span>
        </th>
        <th class="wd-lg-40p">
            <span class="arrow_column">
                Service Name
                <img src="" id="s_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Services::$name?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-40p">
            <span class="arrow_column">
                Department
                <img src="" id="d_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Departments::$name?>"
                     data-order="">
            </span>
        </th>

        <th class="wd-lg-10p ">
            <span class="arrow_column">
                Workers
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