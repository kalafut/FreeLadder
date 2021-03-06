<h2>Essentials</h2>

<div class="tab">
    <h3 id="rules">Game Rules</h3>
    <p>Unless a modified set of match rules is agreed to by both players, the following will apply:</p>
    <ul>
        <li>Matches will be best 2 out of 3 games.</li>
        <li>Games will be played to 11 points and the winner must win by 2 points.</li>
        <li>Serve will change sides every 2 points.</li>
        <li>If the score reaches "deuce" (i.e. tied at least 10-10), serve will change sides after every point.</li>
    </ul>
    <p>The games themselves will follow standard <a id="usatt" href="http://www.usatt.org/rules/index.shtml">USATT rules</a>. A few of the basic rules to follow are:</p>
    <ul>
        <li>Vertically toss (neither drop nor toss sideways) the ball over 6 inches, without imparting spin, from the center of your flat palm before you serve.</li>
        <li>The serve needs to start from behind the endline.</li>
        <li>A missed serve (whether or not the bat contacts the ball) is a point for the other side.</li>
    </ul>


<h3>Getting Started</h3>
<p>FreeLadder is simple to use. Get started in three steps:</p>
<ul>
    <li>Challenge someone ranked higher than you on the ladder.</li>
    <li>Contact your opponent and arrange the match.</li>
    <li>Click on <button class="btn btn-success btn-small">I Won</button> or <button class="btn btn-success btn-small">I Lost</button> to record the result.</li>
</ul>
<p>That's it!  FreeLadder will update the ladder and your personal record. Read on for more detailed instructions.</p>
</div>


<h2>Using FreeLadder</h2>

<div class="tab">
    <h3>Rankings</h3>
    <p>FreeLadder let's you easily participate in a competitive ladder. Ladders are popular in clubs of many disciplines: tennis, squash, ping-pong, chess, etc. A ladder is a ranking, not a rating.  Your ranking is determined by the matches you and others have played recently.  The rankings change on FreeLadder based on some simple rules:</p>
    <ul>
        <li>If the higher ranked player wins, the rankings don't change.</li>
        <li>If the lower ranked player wins, they take the place of their opponent. The players in between (if there are any) will shift down one spot.</li>
    </ul>

    <a name="ratings"></a>
    <p>It is important to understand that rankings are updated when match is saved. In an active ladder, players are constantly moving up and down in rank at the same time others are placing challenges and completing matches. When both players agree on results, the match is final and the rankings are updated based on the ladder at that instant. In other words, the ranking changes might end up being different than what you expect when you first place challenge.</p>

    <h3>Ratings</h3>
    <p>Ratings are independent of rankings and are an estimate of one's skill relative to others. They're calculated after each match based on each
       player's current rating and rating confidence. The ratings system being used is called <a href="http://www.glicko.net/glicko.html">Glicko 2</a>.
       It is well-regarded and has a solid statistical foundation. The USATT formula, on the other hand, has a lot of shortcomings and isn't good for match-by-match results.
       In addition to the ratings shown on the home page, there is an error estimate on each profile page. This error represents the 68% confidence range
       for the rating and will go down as the player logs more matches.</p>
    <p>Giving the ratings difference between two accurate ratings, the probability that the player with the lower rating will upset their opponent is roughly:
    <table id="glicko_odds" style="margin: 1em; padding: 1em;">
        <tr>
            <td>50 points:</td><td>33%</td>
        </tr>
        <tr>
            <td>100 points:</td><td>15%</td>
        </tr>
        <tr>
            <td>150 points:</td><td>10%</td>
        </tr>
        <tr>
            <td>200 points:</td><td>5%</td>
        </tr>
    </table>
</p>

    <p>The FreeLadder ratings are only comparable within this ladder. You can't compare them to USATT ratings in any meaningful way.</p>
    <h3>Challenges</h3>
    <p>
    You may challenge anyone who has <button class="btn btn-primary btn-small">Challenge</button> by their name.  Whether a button is present depends on a number of factors including the setup of the ladder and that player's status and settings (more on them below).  A common ladder setting is a challenge window, meaning you can only challenge someone within a certain number of spots above your rank.  If you challenge someone, their name will move to the "Pending Matches" until the match is completed or forfeited.</p>

    <p>FreeLadder works on the assumption that if you are marked as available to play, that you will accept and play out any valid challenge.  There is no "Reject" or "Cancel" button.  If for some reason you cannot complete the match, you should forfeit.
    </p>

    <p><strong>New users:</strong> when you join you will be unranked. You can challenge any ranked player in the ladder.  If you win you will take their spot in the ladder, but if you lose you'll start at the bottom.</p>

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

    <h3>General Tips</h3>
    <ul>
        <li><b>Understand what "Active" means</b> &mdash; If your status (set in Profile) is set to "Active" for a given ladder, then you are saying to everyone that you're accepting challenges and ready to play.  If you don't want to play or won't be around, set your status to "Inactive".</li>
        <li><b>Challenges aren't cancelled</b> &mdash; Once you challenge someone or have been challenge, there is no "undo". You are expected to play the match and record the result.</li>
        <li><b>Results are simply win/lose</b> &mdash; FreeLadder only cares who won/lost a match. You will not be asked for individual game scores. Refer to your ladder's rules to learn how matches will be played and winners decided.</li>
        <li><b>Agree on results</b> &mdash; Processing results on FreeLadder is pretty simple: you and your opponent enter who won or lost. If you agree, the match is saved.  Otherwise, then you will prompted to either change your answer or wait for your opponent to.  Like challenges, there is no cancelling of the completed match. it is expected that both sides will come to an agreed upon result.</li>
    </ul>

    <input type="text" tabindex="0" style="display:none;">
    <script>
        $("button").button();
    </script>
</div>

