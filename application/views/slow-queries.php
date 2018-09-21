<import file="application/views/header"/>
<form action="" method="POST">
    <std:if condition="${data.status}">
        <input type="hidden" name="status" value="0"/>
        <input type="submit" value="STOP PROFILING"/>
    <std:else>
        <input type="hidden" name="status" value="1"/>
        <input type="submit" value="START PROFILING"/>
    </std:if>
</form>

<div class="user-table">
<!-- user-table__header -->
<div class="user-table__header">
    <div>DATE</div>
    <div>HOST</div>
    <div>URL</div>
    <div>DURATION</div>
    <div>QUERY</div>
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
            <div>${info.query}</div>
        </div>
    </std:foreach>
</div>
<!-- /user-table__content -->
</div>
<import file="application/views/header"/>
