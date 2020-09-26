<?php include('includes/header.php'); ?>
    <?php 
    $album_id = $_GET['album_id'];
    
    $albumQuery = mysqli_query($con, "SELECT albums.name AS album, artists.name AS artist, artists.id AS bleh, album_art_path FROM albums 
    INNER JOIN artists ON albums.artist = artists. id WHERE albums.id =".$album_id );
    $album = mysqli_fetch_array ($albumQuery,  MYSQLI_ASSOC);
    

    echo"
    <div class=\"entityInfo\"
    style = \"padding: 100px 0px 10px 30px; display: inline-block; width: 100%;\">

    <div class=\"leftSection\" 
    style =\"width: 30%; float: left; max-width: 250px;\">
	<img src=\"assets/" .$album['album_art_path']."\" style = \"width: 100%; \">
	</div>

    <div class=\"rightSection\"
    style = \"width: 70%; float: left; padding: 5px 10px 5px 40px; box-sizing: border-box;\">
        <h1 style =\"margin-top: 0px; color: silver;\">". $album['album']. "</h1>    
        <h2 onclick='location.href=\"artist_song_view.php?artist_id=".$album['bleh']. "\"'
        style =\"margin-top: 0px; color: silver; cursor: pointer;\">". $album['artist']. "</h2>
		<p role=\"link\" tabindex=\"0\" style =\"color: #939393; font-weight: 200;\"></p>
        <p style =\"color: #939393; font-weight: 200;\">";
        $songQuery = mysqli_query($con, "SELECT count(id) AS sum FROM songs WHERE album =". $album_id);
        echo mysqli_fetch_array ($songQuery,  MYSQLI_ASSOC)['sum']." songs</p>

	</div>

</div>


<div class=\"tracklistContainer\" style=\"padding: 0px 0px 5px 30px\" >
    <ul class=\"tracklist\" 
    style=\" background-color: #2b2b2b\">";
		
		$songQuery = mysqli_query($con, "SELECT * FROM songs WHERE album =". $album_id." ORDER BY track_no");
        
        echo "<li class='tracklistRow' style=\"padding: 5px 5px 5px 0px; margin-bottom: 5px; border-top: 1px solid #a0a0a0; border-bottom: 1px solid #a0a0a0;\">
					<div class='trackCount'>
						<span class='trackNumber' style=\"padding: 0px 0px 0px 63px;\" > # </span>
                    </div>
                    
					<div class='trackInfo'>
						<span class='trackName'style=\"padding: 0px 0px 0px 10px;\"> Track Name </span>
						
					</div>
					
					<div class='trackDuration'>
						<span class='duration'> Duration </span>
                    </div>
                    
                </li>";
        
         $i=1;
        while($row = mysqli_fetch_array($songQuery)) {
            
			echo "<li  class='tracklistRow'>
                    <div class='trackCount'>
                        <span class='trackNumber'style=\"font-size: 15px;\">
                        <audio id = 'Audio".$i."' src = \"assets/".$row['file_path']."\" ></audio>
                        <img id ='play".$i."' src='assets/images/icon/play.png' onclick = 'play(".$i.", ".$row['id'].")'>".$row['track_no'].
                        "</span>
                    </div>
                    
                    <div id= 'songs' class='trackInfo' >
                        
                        <span class='trackName' style=\"font-size: 17px; color: #fff;\">" . $row['title'] . "</span>
                        <span class='trackArtist' style=\"font-size: 14px;\">" . $album['artist'];
                        if(isset($row['featuring_artist'])) echo ", ".$row['featuring_artist'];
                        echo "</span>
                    </div>
                    
					
					<div class='trackDuration'>
						<span class='duration' style=\"font-size: 15px;\">" . $row['duration'] . "</span>
                    </div>
                    
				</li>";
                $i++;
		}
        echo "</ul>";
        ?>
<script>
    var audio;
    function play(id, temp) {
        audio = document.getElementById("Audio" + id);
        var button = document.getElementById("play"+ id);
        
        if(audio.paused) {
            audio.play();
            button.src= "assets/images/icon/play.png";
            button.style.opacity = "60%";
        }
        else {
            audio.pause();
            button.src = "assets/images/icon/pause.png";
            button.style.opacity = "20%";
        }
        if(audio.currentTime === 0) {up(temp);};
    };
    function up(temp){
        $.post("includes/updateStreams.php", {songId: temp});
    };
</script>
<style>
                    .tracklistRow {
                        height: 5vh;
                        padding: 15px 10px;
                        list-style: none;
                        background-color: #2b2b2b;
                    }
                    #songs:hover{
                        border: outset;
                    }
                    
                    .tracklistRow span {
                        
                        color: #939393;
                        font-weight: 200;
                    }
                    
                    .tracklistRow .trackCount {
                        
                        width: 18%;
                        float: left;
                    }
                    
                    .tracklistRow .trackCount span {
                        visibility: visible;
                    }
                    
                    .tracklistRow .trackCount img {
                        margin: 0 30px -5px 0px;
                        width: 20px;
                        cursor: pointer;
                        opacity : 10%;
                    }
                    .tracklistRow .trackCount img:hover {
                        opacity :100%;
                    }
                    .tracklistRow .trackInfo {
                        width: 65%;
                        float: left;
                        color: #fff;
                        margin-left: -4px;
                        margin-bottom: 7px;
                        padding-left: 15px;
                     }
                    
                    .tracklistRow .trackInfo span {
                        display: block;
                    }
                    
                    .tracklistRow .trackDuration {
                        width: 12%;
                        float: left;
                        text-align: right;
                    }
                    

                
                    </style>
</div>

</body>
</html>