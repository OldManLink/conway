/*
 * Auxilliary functions to make the statistics work
 *
 */

var pendingUI = null;

function addStatsPanel()
{
    var tButton = document.getElementById('drawerHandle');
    tButton.parentNode.removeChild(tButton);
    var tContainer = document.getElementById('container');
    tContainer.appendChild(getPanel());
    tContainer.appendChild(getDrawerButton());
    selectUI();
}

function getPanel()
{
    var tShowing = getConwayData('stats');
    var tPanel = document.createElement('div');
    tPanel.id = 'panel';
    if(tShowing) tPanel.classList.add('showing');
    tPanel.appendChild(getStats());
    tPanel.appendChild(getUISelect());
    if(!getConwayData('pause'))
    {
        tPanel.appendChild(getPauseButton());
    }
    return tPanel;
}

function getConwayData(attribute)
{
    var data = JSON.parse(getConwayElement().value);
    return data[attribute];
}

function setConwayData(attribute, value)
{
    var data = JSON.parse(getConwayElement().value);
    data[attribute] = value;
    getConwayElement().value = JSON.stringify(data);
}

function getConwayElement()
{
    return document.getElementsByName('data')[0];
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
    tPause.innerHTML = 'Pause';
    tPause.addEventListener('click', pauseResume);
    return tPause;
}

function pauseResume()
{
    toggleConwayData('pause');
    setConwayData('then', 0);
    document.getElementById('theForm').submit();
}

function getUISelect()
{
    var tUISelect = document.createElement('div');
    tUISelect.id = 'uiSelect';
    var tLabel = document.createElement('span');
    tLabel.innerHTML='Interface: ';
    tUISelect.appendChild(tLabel);
    var tSelect = document.createElement('select');
    tSelect.id = 'uiSelector';
    tSelect.appendChild(getUIOption('standard', 'Standard'));
    tSelect.appendChild(getUIOption('hints', 'Century Hints'));
    tSelect.appendChild(getUIOption('speed', 'Speed Practice'));
    tSelect.addEventListener('change', selectUI);
    tUISelect.appendChild(tSelect);
    return tUISelect;
}

function getUIOption(uiType, uiName)
{
    var tSelected = getConwayData('ui') === uiType;
    var tOption = document.createElement('option');
    tOption.value=uiType;
    tOption.innerHTML=uiName;
    if(tSelected)
    {
        tOption.selected='selected';
    }
    else
    {
        tOption.removeAttribute('selected');
    }
    return tOption;
}

function selectUI()
{
    var tSelectedUIName = getSelectedUIName();
    setConwayData('ui', tSelectedUIName);
    switchUI(getCurrentUIName(), tSelectedUIName)
}

function switchUI(oldUIName, newUIName)
{
    if(oldUIName !== 'paused' && oldUIName !== newUIName)
    {
        if ([oldUIName, newUIName].indexOf('speed') >= 0)
        {
            var tPendingUI = getPendingUI();
            var tOldUI = getCurrentUI();
            var tForm = tOldUI.parentNode;
            tForm.removeChild(tOldUI);
            tForm.appendChild(tPendingUI);
            setPendingUI(tOldUI);
        }
        if(newUIName !== 'speed')
        {
            if(getCurrentUIName() !== newUIName)
            {
                var tSelectionIndex;
                if(newUIName === 'standard')
                {
                    tSelectionIndex = 0;
                }
                else
                {
                    tSelectionIndex = (getConwayData('century') + 6) % 7;
                }
                document.getElementsByName('guess')[0].selectedIndex = tSelectionIndex;
                getCurrentUI().setAttribute('name', newUIName);
            }
        }
    }
}

function getCurrentUIName()
{
    return getCurrentUI().getAttribute('name');
}

function getCurrentUI()
{
    return document.getElementById('ui');
}

function getSelectedUIName()
{
    var tUIList = document.getElementById('uiSelector');
    var tSelectedUIOption = tUIList.options[tUIList.selectedIndex];
    return tSelectedUIOption.value;
}

function getPendingUI()
{
    if(pendingUI === null)
    {
        pendingUI = getSpeedUI();
    }
    return pendingUI;
}

function setPendingUI(theUI)
{
    pendingUI = theUI;
}

function getSpeedUI()
{
    var tDay = getConwayData('day');
    var tSpeedUI = document.createElement('div');
    tSpeedUI.id = 'ui';
    tSpeedUI.setAttribute('name', 'speed');
    tSpeedUI.align='center';
    var tGuess = document.createElement('input');
    tGuess.setAttribute('name', 'guess');
    tGuess.type='hidden';
    tSpeedUI.appendChild(tGuess);
    var tTopRow = document.createElement('div');
    tTopRow.align='center';
    addDayButton(tTopRow, 'conwaySpeed_We', 3, '&nbsp;W&nbsp;', tDay);
    addDayButton(tTopRow, 'conwaySpeed_Th', 4, '&nbsp;T&nbsp;', tDay);
    addDayButton(tTopRow, 'conwaySpeed_Fr', 5, '&nbsp;F&nbsp;', tDay);
    tSpeedUI.appendChild(tTopRow);
    var tMiddleRow = document.createElement('div');
    tMiddleRow.align='center';
    addDayButton(tMiddleRow, 'conwaySpeed_Tu', 2, '&nbsp;T&nbsp;', tDay);
    tMiddleRow.appendChild(getSpeedDate());
    addDayButton(tMiddleRow, 'conwaySpeed_Sa', 6, '&nbsp;S&nbsp;', tDay);
    tSpeedUI.appendChild(tMiddleRow);
    var tBottomRow = document.createElement('div');
    tBottomRow.align='center';
    addDayButton(tBottomRow, 'conwaySpeed_Mo', 1, '&nbsp;M&nbsp;', tDay);
    addDayButton(tBottomRow, 'conwaySpeed_Su', 0, '&nbsp;S&nbsp;', tDay);
    tSpeedUI.appendChild(tBottomRow);
    return tSpeedUI;
}

function addDayButton(row, id, value, text, correctAnswer)
{
    tButton = document.createElement('button');
    tButton.id = id;
    if(value == correctAnswer)
    {
        tButton.classList.add('correctAnswer');
    }
    tButton.setAttribute('value', value);
    tButton.innerHTML = text;
    tButton.addEventListener('click', submitSpeed(value));
    row.appendChild(tButton);
}

function submitSpeed(value)
{
    return function()
    {
        document.getElementsByName('guess')[0].value = value;
    };
}

function getSpeedDate()
{
    var tSpeedDate = document.createElement('span');
    tSpeedDate.id = 'speedDate';
    tSpeedDate.align = 'center';
    tSpeedDate.innerHTML = document.getElementById('theDate').innerHTML;
    return tSpeedDate;
}

function getDrawerButton()
{
    var tButton = document.getElementById('drawerHandle');
    if (tButton === null)
    {
        tButton = document.createElement('button');
        tButton.id = 'drawerHandle';
        tButton.innerHTML = '=';
        tButton.addEventListener('click', togglePanelShowing);
    }
    return tButton;
}

function togglePanelShowing()
{
    document.getElementById('panel').classList.toggle('showing');
    toggleConwayData('stats');
}
