<div class="prepend-3 span-18 append-3 last">
    <h2>Getting Started</h2>
    <p>FreeLadder is simple to use. Get started in three steps:</p>
    <ul>
        <li>Challenge someone ranked higher than you on the ladder.</li>
        <li>Contact your opponent and arrange the match.</li>
        <li>Click on <button class="resultButton">I Won</button> or <button class="resultButton">I Lost</button> to record the result.</li>
    </ul> 
    <p>That's it!  FreeLadder will update the ladder and your personal record. Read on for more detailed instructions.</p>
</div>
<!--
<div class="span-9 append-3 last">
    <div style="background-color:tan">
        <a href="#getting_started">Getting Started</a><br>
        Using FreeLadder
        <div style="text-indent:3em">
            Rankings<br/>
            Challenges<br/>
        </div>
    </div>
</div>
-->

<div class="prepend-3 span-18 append-3 last">
    <h2>Using FreeLadder</h2>

    <h3>Rankings</h3>
    <p>FreeLadder let's you easily participate in a competitive ladder. Ladders are popular in clubs of many disciplines: tennis, squash, ping-pong, chess, etc. A ladder is a ranking, not a rating.  Your ranking is determined by the matches you and others have played recently.  The rankings change on FreeLadder based on some simple rules:</p>
    <ul>
        <li>If the higher ranked player wins, the rankings don't change.</li>
        <li>If the lower ranked player wins, they take the place of their opponent. The players in between (if there are any) will shift down one spot.</li>
    </ul> 

    <p>It is important to understand that rankings are updated when match is saved. In an active ladder, players are constantly moving up and down in rank at the same time others are placing challenges and completing matches. When both players agree on results, the match is final and the rankings are updated based on the ladder at that instant. In other words, the ranking changes might end up being different than what you expect when you first place challenge.</p> 

    <!--
    <h3>Ladders</h3>
    <p><i>At present you may only work with one ladder.</i></p>
    -->

    <h3>Challenges</h3>
    <p>
    You may challenge anyone who has <button class="challengeButton">Challenge</button> by their name.  Whether a button is present depends on a number of factors including the setup of the ladder and that player's status and settings (more on them below).  A common ladder setting is a challenge window, meaning you can only challenge someone within a certain number of spots above your rank.  If you challenge someone, their name will move to the "Pending Matches" until the match is completed or forfeited.</p> 

    <p>FreeLadder works on the assumption that if you are marked as available to play, that you will accept and play out any valid challenge.  There is no "Reject" or "Cancel" button.  If for some reason you cannot complete the match, you should forfeit.
    </p>

    <h3>Profile</h3>
    <p>
    The Profile page lets you manage basic login information and customizes your FreeLadder settings:
    <ul>
        <li>Email address can be any valid email address than is not already being used.</li>
        <li>Email notifications can optionally be sent to you when new matches or results have posted. (This feature has not been implemented yet.)</li>
        <li>The status setting controls your visibility on the site and whether you're accepting challenges:</li>
        <ul>
            <li><b>Active</b>&mdash;you are accepting challenges and completing matches.</li>
            <li><b>Inactive</b>&mdash;you are <i>not</i> accepting challenges at this time, nor can you challenge anyone. When you change to this status, you remain in the ladder and will move down in rank if players from below you beat others above you.</li>
            <!--<li><b>Disabled</b>&mdash;you are <i>not</i> accepting or placing challenges, and are not visible to others. Your records are maintained while disabled, but when you are again Active you will reenter the ladder at the bottom.</li>-->
        </ul>
        <li>Limit the number of outstanding challenges you'll allow. After you've reached the limit set here, other's will not be able to challenge you. This is useful if your playing time is limited and you want to avoid a backlog of challenges.</li>
        <li>Your password can be changed (a confirmation is required). Leave these field blank to keep your current password.</li>
    </ul>

    <h3> General Tips</h3>
    <ul>
        <li><b>Understand what "Active" means</b> &mdash; If your status (set in Profile) is set to "Active" for a given ladder, then you are saying to everyone that you're accepting challenges and ready to play.  If you don't want to play or won't be around, set your status to "Inactive".</li>
        <li><b>Challenges aren't cancelled</b> &mdash; Once you challenge someone or have been challenge, there is no "undo". You are expected to play the match and record the result.</li>
        <li><b>Results are simply win/lose</b> &mdash; FreeLadder only cares who won/lost a match. You will not be asked for individual game scores. Refer to your ladder's rules to learn how matches will be played and winners decided.</li>
        <li><b>Agree on results</b> &mdash; Processing results on FreeLadder is pretty simple: you and your opponent enter who won or lost. If you agree, the match is saved.  Otherwise, then you will prompted to either change your answer or wait for your opponent to.  Like challenges, there is no cancelling of the completed match. it is expected that both sides will come to an agreed upon result.</li>
    </ul> 
</div>
<script> 
    $("button").button();
</script> 

