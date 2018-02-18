/*
 * Auxilliary functions to make the statistics work
 *
 */
 
 function addStatsPanel()
{
    removeDummyDrawerButton();
    var tContainer = document.getElementById('container');
    tContainer.appendChild(getPanel());
    tContainer.appendChild(getDrawerButton());
}

function getPanel()
{
    var tShowing = getConwayData('stats');
    var tPanel = document.createElement('div');
    tPanel.id = 'panel';
    if(tShowing) tPanel.classList.add('showing');
    tPanel.appendChild(getStats());
    tPanel.appendChild(getHints());
    tPanel.appendChild(getSkipButton());
    return tPanel;
}

function getConwayData(attribute)
{
    var data = JSON.parse(document.getElementsByName('data')[0].value);
    return data[attribute];
}

function setConwayData(attribute, value)
{
    var data = JSON.parse(document.getElementsByName('data')[0].value);
    data[attribute] = value;
    document.getElementsByName('data')[0].value = JSON.stringify(data);
}

function getStats()
{
    var tStats = document.createElement('div');
    tStats.id = 'statistics';
    tStats.innerHTML = getStatsText();
    return tStats;
}

function getHints()
{
    var tHints = document.createElement('div');
    tHints.id = 'hints';
    tHints.align = 'center';
    var cb = document.createElement('input');
    cb.id = 'useHints';
    cb.type = 'checkbox';
    cb.checked = getConwayData('hints');
    cb.addEventListener('click', updateHints);
    tHints.appendChild(cb);
    var label = document.createElement('span');
    label.innerHTML = ' use Century hints';
    tHints.appendChild(label);
    return tHints;
}

function updateHints()
{
    var tChecked = document.getElementById('useHints').checked;
    setConwayData('hints', tChecked);
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
            + 'Guesses: ' + tGuesses.length + ', Skipped: ' + tSkips;
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
    setConwayData('then', -1);
    document.getElementById('theForm').submit();
}

function removeDummyDrawerButton()
{
    var tButton = document.getElementById('drawerHandle');
    tButton.parentNode.removeChild(tButton);
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
    document.getElementById('panel').classList.toggle('showing');
    setConwayData('stats', !getConwayData('stats'));
}
