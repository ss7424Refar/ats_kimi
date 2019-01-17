// |--------------const-------------|
// task status
const Pending = 'pending';
const Ongoing = 'ongoing';
const Finish = 'finished';
const Pass = 'pass';
const Fail = 'fail';
const Expired = 'expired';

// tool name
const JumpStart = 'JumpStart';
const Recovery = 'Recovery';
const C_Test = 'C-Test';

// times
const Hour = 'hour';
const Day = 'day';
const Week = 'week';
const Month = 'month';
const Year = 'year';


// |--------------dash_board.html-------------|
function getDaysOfMonth() {
    var now = new Date();
    var year = now.getFullYear();       //年
    var month = now.getMonth() + 1;     //月
    var days = (new Date(year, month, 0)).getDate();
    return days;
}

/**
 * 判断是否null
 * @param data
 */
function isNull(data){
    return (null !== data && undefined !== data && 0 !== data.length) ? false : true;
}

function getToday() {
    return moment().format('YYYY-MM-DD');
}
function getThisWeek() {
    return '(' + moment().subtract(6, 'days').format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD') + ')';
}
function getThisMonth() {
    return '(' + moment().startOf('month').format('YYYY-MM-DD') + ' - ' + moment().endOf('month').format('YYYY-MM-DD') + ')';
}
function getThisYear() {
    var myDate = new Date();
    var year = myDate.getFullYear();//获取当前年
    return '(' + year + ')';
}

function getHeader(timers) {

    if (Hour === timers || Day === timers) {
        return getToday();
    } else if (Week === timers) {
        return getThisWeek();
    } else if (Month === timers) {
        return getThisMonth();
    } else if (Year === timers) {
        return getThisYear();
    }

}

// |--------------task_manager.html-------------|
// 相差几个月
function timeDifference(date1, date2){
    var newYear = date1.getFullYear();
    var newMonth =date1.getMonth() + 6;

    if(newMonth >= 11){
        newYear += 1;
        newMonth -= 11;
        date1.setFullYear(newYear);
        date1.setMonth(newMonth-1);
    }
    else{
        date1.setFullYear(newYear);
        date1.setMonth(newMonth);
    }
    if(date1.getTime() >= date2.getTime()){
        return true;
    }
    else{
        return false;
    }
}
