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
                     data-column="<?=\Src\Model\Table\Workers::$id?>"
                     data-order="asc">
            </span>
        </th>
        <th class="wd-lg-8p">
            <span class="arrow_column">
                Name
                <img src="" id="img_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Workers::$name?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-10p">
            <span class="arrow_column">
                Surname
                <img src="" id="login_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Workers::$surname?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-15p">
            <span class="arrow_column">
                Email
                <img src="" id="email_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Workers::$email?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-10p">
            <span class="arrow_column">
                Position
                <img src="" id="email_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Positions::$name?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-8p">
            <span class="arrow_column">
                Salary
                <img src="" id="email_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Workers::$salary?>"
                     data-order="">
            </span>
        </th>
        <th class="wd-lg-2p">
            <span class="arrow_column">
                Experience
                <img src="" id="email_arrow" class="sort_arrow"
                     data-column="<?=\Src\Model\Table\Workers::$years_of_experience?>"
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