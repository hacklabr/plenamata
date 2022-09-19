import { sprintf } from '@wordpress/i18n'

import { __ } from '../dashboard/plugins/i18n'
import { fetchDeterData, fetchLastDate } from '../utils/api'
import { roundNumber, shortDate } from '../utils/filters'

const { DateTime, Interval } = window.luxon

document.defaultView.document.addEventListener('DOMContentLoaded', async () => {
    const updated = await fetchLastDate()
    const lastDate = DateTime.fromISO(updated.deter_last_date, { zone: 'utc' })

    const now = DateTime.now()
    const startOfYear = lastDate.startOf('year')
    const thisYear = await fetchDeterData({ data1: startOfYear.toISODate(), data2: updated.deter_last_date })

    const daysThisYear = Interval.fromDateTimes( startOfYear, lastDate );

    const lastWeek = await fetchDeterData({ data1: lastDate.minus({ days: 6 }).toISODate(), data2: updated.deter_last_date });

    var lastFriday = DateTime.fromObject({ weekday: 5, hour: 3 });

	document.querySelectorAll('[data-deter]').forEach((el) => {
        const deterLabel = el.dataset.deter

        if( deterLabel === 'hectaresLastWeek' ){
            
            const hectaresLastWeek = Number( lastWeek[0].areamunkm ) * 100;
            el.textContent = roundNumber( hectaresLastWeek );
        
        }
        else if( deterLabel === 'hectaresPerDay' ){
            
            const hectaresPerDay = ( Number( thisYear[0].areamunkm ) * 100 ) / daysThisYear.count( 'days' );
            el.textContent = roundNumber( hectaresPerDay )
        
        }
        else if( deterLabel === 'sourcesLastWeek' ){
            
            const sourcesLastWeek = sprintf( __( 'Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata' ), shortDate( updated.last_sync ), shortDate( updated.deter_last_date ) );
            el.textContent = sourcesLastWeek;
        
        }
        else if( deterLabel === 'hectaresThisYear' ){

            const areaThisYear = Number( thisYear[0].areamunkm );
            const areaLastWeek = Number( lastWeek[0].areamunkm );
            const areaPerSecondLastWeek = areaLastWeek / 604800;

            if( now < lastFriday ){
                lastFriday = lastFriday.minus({ weeks: 1 });
            }

            const startDate = ( lastFriday.year === now.year ) ? lastFriday : now.startOf( 'year' );
            const elapsedTime = Interval.fromDateTimes( startDate, now );

            let areaCount = ( areaThisYear - areaLastWeek ) + ( elapsedTime.count( 'seconds' ) * areaPerSecondLastWeek );
            el.textContent = roundNumber( areaCount * 100 );

            setInterval(() => {
                areaCount += areaPerSecondLastWeek
                el.textContent = roundNumber( areaCount * 100 );
            }, 1000 );
        
        }
        else if( deterLabel === 'treesEstimative' ){
        
            const 
                treesThisYear = Number(thisYear[0].num_arvores),
                treesLastWeek = Number(lastWeek[0].num_arvores),
                treesPerSecondLastWeek = treesLastWeek / 604800
            ;

            if( now < lastFriday ){
                lastFriday = lastFriday.minus({ weeks: 1 });
            }

            const startDate = ( lastFriday.year === now.year ) ? lastFriday : now.startOf( 'year' );
            const elapsedTime = Interval.fromDateTimes( startDate, now );

            let treeCount = ( treesThisYear - treesLastWeek ) + ( elapsedTime.count( 'seconds' ) * treesPerSecondLastWeek );
            el.textContent = roundNumber( treeCount );

            setInterval(() => {
                treeCount += treesPerSecondLastWeek
                el.textContent = roundNumber( treeCount );
            }, 1000 );
        
        }
        else if( deterLabel === 'treesPerDay' ){
            
            const treesPerDay = Number( thisYear[0].num_arvores ) / daysThisYear.count( 'days' );
            el.textContent = roundNumber( treesPerDay );
        
        }
    
    });

    document.querySelectorAll('[data-mask=true]').forEach((el) => {
        const num = Number(el.textContent)
        if (num) {
            el.textContent = roundNumber(+num)
        }
    })
})
