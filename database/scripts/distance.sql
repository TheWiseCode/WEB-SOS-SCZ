-- drop function distance
create function distance(
    lat1 float, lon1 float, lat2 float, lon2 float
)
    returns float
    language plpgsql
as
$$
DECLARE
    distance float;
    radius float := 6378.127;
    deg2radMultiplier float := PI() / 180;
    dlongitud float;
begin
    lat1 = lat1 * deg2radMultiplier;
    lon1 = lon1 * deg2radMultiplier;
    lat2 = lat2 * deg2radMultiplier;
    lon2 = lon2 * deg2radMultiplier;
    dlongitud := lon2 - lon1;
    distance = ABS(ACOS(SIN(lat1) * SIN(lat2) + COS(lat1) * COS(lat2) * COS(dlongitud)) * radius);
    /*if(unit = 'M') then
        distance = distance * 1000;
    end if*/
    return distance;
end;
$$
