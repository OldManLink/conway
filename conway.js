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
    setPauseResumeText();
}

function getPanel()
{
    var tShowing = getConwayData('stats');
    var tPanel = document.createElement('div');
    tPanel.id = 'panel';
    if(tShowing) tPanel.classList.add('showing');
    tPanel.appendChild(getStats());
    tPanel.appendChild(getPauseButton());
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

function toggleConwayData(booleanAttribute)
{
    setConwayData(booleanAttribute, !getConwayData(booleanAttribute));
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
    var tGuesses = JSON.parse(document.getElementsByName('guesses')[0].value);
    var tSuccesses = tGuesses.filter(function(guess) { return guess !== -1; });
    var tSuccessRate = Math.round(tSuccesses.length / tGuesses.length * 1000) / 10;
    var tTotalTime = tSuccesses.reduce(function(acc, val) { return acc + val; }, 0);
    var tAverageTime = Math.round(tTotalTime / tSuccesses.length / 10) / 100;
    if (isNaN(tSuccessRate) || isNaN(tAverageTime))
    {
        return 'Insufficient data to display<br>success rate/average time.<br>'
            + 'Guesses: ' + tGuesses.length;
    } else
    {
        return 'Success rate: ' + tSuccessRate + '%<br>'
            + 'Average time: ' + tAverageTime + ' secs<br>'
            + 'Guesses: ' + tGuesses.length;
    }
}

function getPauseButton()
{
    var tPause = document.createElement('button');
    tPause.id = 'conway_pause';
    tPause.addEventListener('click', pauseResume);
    return tPause;
}

function pauseResume()
{
    toggleConwayData('pause');
    setConwayData('then', 0);
    setPauseResumeText();
    document.getElementById('theForm').submit();
}

function setPauseResumeText()
{
    var tPauseButton = document.getElementById('conway_pause');
    tPauseButton.innerHTML = getConwayData('pause') ? 'Resume' : 'Pause';
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
    toggleConwayData('stats');
}
