<import file="application/views/header"/>

<link rel="stylesheet" href="/plugins/pcms_profiler/public/css/slow-pages.css?version=${data.version}"/>

<form action="" method="POST">
    <std:if condition="${data.status}">
        <input type="hidden" name="status" value="0"/>
        <input type="submit" value="STOP BENCHMARKING"/>
        <std:else>
            <input type="hidden" name="status" value="1"/>
            <input type="submit" value="START BENCHMARKING"/>
    </std:if>
</form>

<div class="user-table">
    <!-- user-table__header -->
    <div class="user-table__header">
        <div>DATE</div>
        <div>HOST</div>
        <div>URL</div>
        <div>DURATION</div>
    </div>
    <!-- /user-table__header -->

    <!-- user-table__content -->
    <div class="user-table__content">
        <std:foreach var="${data.results}" value="info">
            <div class="user-table__row">
                <div>${info.date}</div>
                <div>${info.host}</div>
                <div>${info.url}</div>
                <div>${info.duration}</div>
            </div>
        </std:foreach>
    </div>
    <!-- /user-table__content -->
</div>
<import file="application/views/footer"/>