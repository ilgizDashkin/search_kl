import React from 'react';
import { Button,Div,Card,CardGrid,Group } from '@vkontakte/vkui';//пакеты из вк
import Icon24Shuffle from '@vkontakte/icons/dist/24/shuffle';
import Icon24Note from '@vkontakte/icons/dist/24/note';
import Icon24Info from '@vkontakte/icons/dist/24/info';

export default props => (
    <div className='container p-2 border'>
        <p>найдено: {props.data.length}, в среднем длина КЛ: {props.lenght?props.lenght:'неизвестно'} м.</p>
    <Div style={{display: 'flex'}}>
    <Button stretched onClick={props.onSort.bind(null, 'name')} before={(props.sortField === 'name') ? <Icon24Shuffle/> : null}>сортировка по имени </Button>
    <Button stretched onClick={props.onSort.bind(null, 'zamer')} before={(props.sortField === 'zamer') ? <Icon24Shuffle/> : null}>сортировка по замеру </Button>
    </Div>
    {props.data.map(item => (
              <Group key={item.id}>
              <CardGrid className=" text-dark" >                   
                    <Card size="m"><Button stretched mode="commerce" before={<Icon24Note/>}>{item.name.toLowerCase()}</Button> </Card>
                    <Card size="m"><Button stretched mode="destructive" before={<Icon24Info/>}>{((item.zamer===10000)||(item.zamer===0))?'-':item.zamer}</Button></Card>
                    <Card size="l">{item.priv}</Card>                                      
                </CardGrid>
                </Group>
            ))}
    </div>
)

