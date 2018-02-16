/*
 * Auxilliary functions to make the statistics work
 *
 */
 
 function addStatsPanel()
{
    var tContainer = document.getElementById('container');
    tContainer.appendChild(getPanel());
    tContainer.appendChild(getDrawerButton());
}

function getPanel()
{
    var tShowing = JSON.parse(getStatsInput().value);
    var tPanel = document.createElement('div');
    tPanel.id = 'panel';
    if(tShowing) tPanel.classList.add('showing');
    tPanel.appendChild(getStats());
    tPanel.appendChild(getSkipButton());
    return tPanel;
}

function getStatsInput()
{
    return document.getElementsByName('stats')[0];
}

function getStats()
{
    var tStats = document.createElement('div');
    tStats.id = 'statistics';
    tStats.innerHTML = getStatsText();
    return tStats;
}

function getStatsText()
{
    var tTimes = JSON.parse(document.getElementsByName('times')[0].value);
    var tSkips = tTimes.filter(function(time) { return time === 0; }).length;
    var tGuesses = tTimes.filter(function(time) { return time !== 0; });
    var tSuccesses = tGuesses.filter(function(time) { return time !== -1; });
    var tSuccessRate = Math.round(tSuccesses.length / tGuesses.length * 1000) / 10;
    var tTotalTime = tSuccesses.reduce(function(acc, val) { return acc + val; }, 0);
    var tAverageTime = Math.round(tTotalTime / tSuccesses.length / 10) / 100;
    if (isNaN(tSuccessRate) || isNaN(tAverageTime))
    {
        return 'Insufficient data for success rate/average time.<br>'
            + 'Skipped: ' + tSkips;
    } else
    {
        return 'Success rate: ' + tSuccessRate + '%<br>'
            + 'Average time: ' + tAverageTime + ' secs<br>'
            + 'Skipped: ' + tSkips;
    }
}

function getSkipButton()
{
    var tSkip = document.createElement('button');
    tSkip.id = 'conway_skip';
    tSkip.innerHTML = 'Skip';
    tSkip.addEventListener('click', skipGuess);
    return tSkip;
}

function skipGuess()
{
    document.getElementsByName('then')[0].value = -1;
    document.getElementById('theForm').submit();
}

function getDrawerButton()
{
    var tButton = document.createElement('button');
    tButton.id = 'drawerHandle';
    tButton.innerHTML = '=';
    tButton.addEventListener('click', togglePanelShowing);
    return tButton;
}

function togglePanelShowing()
{
    var tPanel = document.getElementById('panel');
    tPanel.classList.toggle('showing');
    var tShowing = JSON.parse(getStatsInput().value);
    getStatsInput().value = !tShowing;
}
